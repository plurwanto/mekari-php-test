<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Styles -->
        <link href="{!!url('/assets/plugins/bootstrap/css/bootstrap.min.css')!!}" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="{!!url('/assets/fonts/style.css')!!}">
        <link rel="stylesheet" href="{!!url('/assets/css/main.css')!!}">

    </head>
    <body>
        <!-- BEGIN FORM-->
        <div class="col-md-8">
            <br>
            <div class="row">
                <h1>ToDo List</h1>
                <div class="col-md-12">
                    <div class="well inline-headers">
                        <form class="form-inline" id="form_todo" name="form_todo" method="POST">
                            <input type="hidden" name="txt_todoid" id="txt_todoid">
                            <div class="form-group">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="txt_todoname" maxlength="255" id="txt_todoname" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary" name="btnSave" id="btnSave">Add ToDo</button>
                                </div>
                            </div>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <table class="table table-bordered table-hover table-condensed nowrap" id="todotable">

                            <!-- ajax-->

                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- END FORM-->
    </body>

    <script src="{!!url('/js/jquery.min.js')!!}"></script>

    <script type="text/javascript">
$(document).ready(function () {
    $('#txt_todoid').val('');
    $('#btnSave').attr('disabled', true); //set button disable 

    $("#btnSave").click(function () {
        $.ajax({
            url: '/todo-save',
            type: "post",
            data: {
                "todo_id": $('#txt_todoid').val(),
                "todo_name": $('#txt_todoname').val(),
                "_token": "<?=csrf_token()?>"
            },
            dataType: "JSON",
            success: function (data)
            {
                if (data.response_code == 200) {
                    $('#txt_todoid').val(data.td_id);
                    $('#txt_todoname').val('');
                    $('#txt_todoname').focus();

                    reload_table();
                    $('#btnSave').attr('disabled', true); //set button disable 
                }
            },
        });
    });

    $('#txt_todoname').on('keypress', function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if ((keycode == '13') && $(this).val() != "") {
            $.ajax({
                url: '/todo-save',
                type: "post",
                data: {
                    "todo_id": $('#txt_todoid').val(),
                    "todo_name": $('#txt_todoname').val(),
                    "_token": "<?=csrf_token()?>"
                },
                dataType: "JSON",
                success: function (data)
                {
                    if (data.response_code == 200) {
                        $('#txt_todoid').val(data.td_id);
                        $('#txt_todoname').val('');
                        $('#txt_todoname').focus();

                        reload_table();
                        $('#btnSave').attr('disabled', true); //set button disable 
                    }
                },
            });
        }

    });

    $("#txt_todoname").on('keydown', function () {
        if ($(this).val() != "") {
            $('#btnSave').attr('disabled', false); //set button disable 
        } else {
            $('#btnSave').attr('disabled', true); //set button disable 
        }
    });

    $('form').submit(function (e) {
        e.preventDefault(); // Prevent the original submit
    });

});

function reload_table() {
    $.ajax({
        url: '/todo-list',
        type: "post",
        data: {
            "todo_id": $('#txt_todoid').val(),
            "_token": "<?=csrf_token()?>"
        },
        dataType: "JSON",
        success: function (response)
        {
            if (response.response_code == 200) {
                var no = $('#txt_todoid').val();
                if (no != "") {
                    for (var i = 0; i < response.data.length; i++) {
                        x = i + 1;
                        $('table#todotable tr#baris' + x).remove();
                        html = '<tr id="baris' + x + '" class="clickable-row">';
                        html += '<td width="3%"><input type="checkbox" id="chk"' + x + '></td>';
                        html += '<td width="20%" name="nama[]" id="nama' + x + '">' + response.data[i].todo_name + '</td>';
                        html += '<td width="2%"><div class="action-buttons"><a id="btndel' + x + '" class="red" title="Delete" onclick="delete_item(' + x + ',' + response.data[i].id + ')" style="cursor: pointer;"><i class="glyphicon glyphicon-trash"></i></a></div></td>';
                        html += '</tr>';
                        $('#todotable').append(html);
                    }
                }

            }
        },
    });
}

function delete_item(iteration, rows) {
    $.ajax({
        url: '/todo-delete',
        type: "POST",
        dataType: "JSON",
        data: {
            "id": rows,
            "_token": "<?=csrf_token()?>"
        },
        success: function (response)
        {
            if (response.response_code == 200) {
                $('table#todotable tr#baris' + iteration).remove();
            }
        }
    });
}


    </script>
</html>
