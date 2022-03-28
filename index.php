<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="node_modules/sweetalert2/dist/sweetalert2.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justity-content-center">
            <div class="col-12">
                <form id="form" class="form">
                    <div class="d-grid col-md-1">
                        <a href="#" class="btn btn-primary" id="add">เพิ่มสินค้า+</a>
                    </div>
                    <div id="wrapper">
                        <div class="input-group my-2">
                            <span class="input-group-text">สินค้า:</span>
                            <input type="text" name="name" class="form-control" placeholder="ชื่อสินค้า" required>
                            <input type="number" name="price" class="form-control" placeholder="ราคา" required>
                        </div>
                    </div>
                    <div class="d-grid my-3 col-md-1">
                        <input type="submit" class="btn btn-success" value="บันทึก"></input>
                    </div>
                </form>
            </div>

            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-transparent border-primary">
                        รายการสินค้า
                        <button class="btn btn-outline-danger btn-sm float-end" id="destroy">ล้างข้อมูล</button>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered" id="products">
                            <thead>
                                <tr>
                                    <th width="2%"> <input type="checkbox" id="checkAll" > </th>
                                    <th width="10%" id="bulkAction"> รหัส </th>
                                    <th> ชื่อสินค้า </th>
                                    <th> ราคาขาย </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <nav aria-lable="Multi Input" class="table-responsive d-flex">
                            <ul class="pagination mx-auto"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="show"></div>

<script src="node_modules/jquery/dist/jquery.min.js"></script>
<script src="node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
<script>
    $(document).ready(function() {
        let maxFields = 8,
            proData = [],
            formData = [],
            proTbody = '#products tbody',
            x = 1

        $('#add').on('click', function(e) {
            e.preventDefault()
            if(x < maxFields){
                x++
                $('#wrapper').append(
                    ` <div class="input-group my-2" id="text1">
                        <span class="input-group-text">สินค้า</span>
                        <input type="text" name="name" id="text1" class="form-control" placeholder="ชื่อสินค้า" required>
                        <input type="number" name="price" class="form-control" placeholder="ราคา" required>
                        <a href="#" class="btn btn-danger" id="delete">ลบ</a>
                    </div> `
                )
            } 
        })

        $('#wrapper').on('click','#delete', function(e) {
            e.preventDefault()
            $(this).parent('div').remove()
            x--
        })

        $('#destroy').on('click', function(e) {

        })

        $('#products').on('click', '#deleteItem', function(e) {

        })

        $('#checkAll').on('change', function() {
            let checked = this.checked
            $(':checkbox').prop('checked', checked)
        })

        $('#form').submit(function(e) {
            e.preventDefault()
            $('#form').find('.input-group').each(function (i, element) {
                let inputObj = {}
                $(element).find('input').each(function (index, data) {
                    $.extend(inputObj, { [data.name] : data.value })
                })
                formData.push(inputObj)
            })
            createData(formData)
        })

        function createData(formData){
            // console.log(formData)
            $.ajax({
                type: 'POST',
                url: 'service/product/store.php',
                data: { request: JSON.stringify(formData) }
            }).done(function(resp) {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลเสร็จเรียบร้อย',
                    showConfirmButton: true
                }).then(function(result) {
                    location.reload()
                })
            }).fail(function(resp) {
                Swal.fire({ 
                    icon: 'error',
                    text: 'ไม่สามารถบันทึกขอมูลได้'
                })
            })
        }

        function loadTable(response){
            $(proTbody).html('')
            proData = response
            proData.forEach(function (item, index) {
                $(proTbody).append(
                    `<tr>
                        <td> <input type="checkbox" class="checkDelete" value="${item.id}" /></td>
                        <td> ${String("0000" + item.id).slice(-4)} </td>
                        <td> ${item.name} </td>
                        <td> ${item.price} </td>
                    </tr>`
                )
            })
            $('input[type=checkbox]').change(function() {
                let id = []
                $(".checkDelete:checked").each(function() {
                    id.push($(this).val())
                })
                if(id.length){
                    $('#bulkAction').html('<button class="btn btn-outline-info btn-sm" id="deleteItem"> ลบข้อมูล </button>')
                }else{
                    $('#bulkAction').text('รหัส')
                }
            })
        }

        function loadPaginate(pagination, pageId = ''){
            let paginate = '.pagination'
            let pageLink = ''
            let currentPage = pageId ? JSON.parse(pageId) : 0
            let totalPage = pagination.totalPage
            $(paginate).html('')

            for (i = 1; i <= totalPage; i++) {
              pageLink += `<li class="page-item ${(i == currentPage) ? 'active' : ''}"> 
                                <a class="page-link paginate go" href="#" data-value="${i}">${i}</a>
                            </li>` 
                // let value = i
                // if(currentPage == i){
                //     sub_array = value
                //     console.log(currentPage)                    
                // }
            }


            // for(var i = 1 ; i<= totalPage; i++){
            //     $("#pagin").append(` <li> <a class="page-link paginate go" href="${i}" id="${i}">${i}</a></li> `);
               
            // }


            $(paginate).append(
                `<li class="page-item"><a class="page-link prev" href="#">ก่อนหน้า</a></li>`
                + pageLink +
                `<li class="page-item"><a class="page-link next" href="#">ถัดไป</a></li>`
            ) 


            currentPage == 1 ? $('.prev').parent('li').addClass('disabled') : ''
            currentPage == totalPage ? $('.next').parent('li').addClass('disabled') : ''

            $('.next').on('click', function() {
                if(currentPage < totalPage) {
                    currentPage++
                    loadData(currentPage) 
                }
            })

            $(".go").on('click', function() {
                // console.log($(this).data('value'));
                let page = $(this).data('value');
                loadData(page)
            })

            $('.prev').on('click', function() {
                if(currentPage >= 2) {
                    currentPage--
                    loadData(currentPage)
                }
            })

        }

        // function การยิง Ajax ดึงข้อมูลสินค้า
        function loadData(pageId){
            $.ajax({
                url: 'service/product/paginate.php',
                type: 'GET',
                data: { 
                    pageId: pageId
                 }
            }).done(function(data) {
                // console.log(data.response)
                loadTable(data.response)
                loadPaginate(data.pagination, pageId)         
                $(".patination li a.paginate").on("click", function(e) {
                    e.preventDefault()
                    let pageId = $(this).attr("id")
                    loadData(pageId)
                })
            }).fail(function(error) {
                $(proTbody).html('')
                $(proTbody).append(`<tr><td colspan="4" class="text-center">ข้อมูลว่าง</td></tr>`)
            })
        }

        //เริ่มต้นทำงานดึงข้อมูลสินค้า
        loadData(1)
    
    })
</script>

</body>
</html>