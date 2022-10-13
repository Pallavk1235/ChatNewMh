@extends('layouts.chat_theam')

@section('content')
    <div class="row">
        <div class="col-md-2">
            <div class="container" id="containers">
                <div class="card">
                    <div class="card-header">
                        My Contects
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="contacts">
                            <thead>
                                <tr>
                                    <th>Chats</th>
                                </tr>
                            </thead>
                            <tbody>
                                <input type="hidden" id="auth_name" value="{{ Auth::user()->name }}">
                                <input type="hidden" id="active_chat_user_id"
                                    value="{{ $users[0] ? $users[0]->id : null }}">
                                <input type="hidden" id="active_chat_user_name"
                                    value="{{ $users[0] ? $users[0]->name : null }}">
                                @foreach ($users as $chats)
                                    @if (Auth::user()->name != $chats->name)
                                        <tr id='ClickableRow' class="newData_{{$chats->id}}">
                                            <td>{{ $chats->name }}</td>
                                            <td id="count_{{ $chats->id }}">
                                                {{-- <span class="right badge badge-danger">New</span> --}}
                                            </td>
                                            <td style="display:none">{{ $chats->id }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-muted">
                        <i class="fa fa-whatsapp" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="container" id="containers">
                <div class="card">
                    <meta name="csrf-token" content="{{ csrf_token() }}" />
                    <div class="card-header">Chats</div>
                    <input type="hidden" value="{{ Auth::user()->name }}" id="from_user">
                    <input type="hidden" value="{{ Auth::user()->id }}" id="from_user_id">
                    <div class="card-body">
                        <div class="direct-chat-messages" style="display:none">

                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="input-group hide">
                            <input type="text" class="form-control" id="text-message">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="chat_form">
                                    <i class="fas fa-location-arrow"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var sender_user_id = $("#active_chat_user_id").val(),
        
            CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content'),
            main_name = $('#auth_name').val(),
            all_messages = [],
            main_messages = {},
            main_chats = [],
            abc = [];


        loadMsg()

        function loadMsg() {
            let logged_user = $('#from_user_id').val(),
            active_chat_user_name = $("#active_chat_user_name").val()
            sender_user_id = $("#active_chat_user_id").val()

            var data = {
                _token: CSRF_TOKEN,
                from_user_id: logged_user,
                my_user_id: sender_user_id  
            }
            $('.direct-chat-messages').html("");
            $.ajax({
                url: '/api/chats/loadAllMsg',
                method: 'POST',
                data: data,
                dataType: 'JSON',
                success: function(seen_message) {
                    newMessage(data)

                    for (var i = 0; i < seen_message.length; i++) {
                        if (seen_message[i].user_from == logged_user) {
                            var my_msg =
                                '<div class="direct-chat-msg right"  style="margin-right:45px;"><div class="direct-chat-infos clearfix"><span class="direct-chat-name float-right">' +
                                main_name +
                                '</span></a><span class="direct-chat-timestamp float-left">' +
                                seen_message[i].created_at +
                                '</span></div><div class="direct-chat-text">' +
                                seen_message[i].messages +
                                '</div></div><button class="btn btn-danger delete_msg" data-id="' +
                                seen_message[i].id +
                                '" data-from="' +
                                seen_message[i].user_from +
                                '" data-to="' + seen_message[i].user_to +
                                '" style="float: right;margin-top: -45px;"><i class="fas fa-trash"></i></button>';
                            $('.direct-chat-messages').append(my_msg);
                        } else if (seen_message[i].user_from == sender_user_id) {
                            var my_msg =
                                '<div class="direct-chat-msg"  style="margin-right:45px;"><div class="direct-chat-infos clearfix"><span class="direct-chat-name float-right">' +
                                active_chat_user_name +
                                '</span><span class="direct-chat-timestamp float-left">' +
                                seen_message[i].created_at +
                                '</span></div><div class="direct-chat-text">' +
                                seen_message[i].messages +
                                '</div></div>';
                            $('.direct-chat-messages').append(my_msg);
                        }
                    }

                    $('.direct-chat-messages').show();
                    $('.input-group').show();
                }
            })

            // $(document).on("click", ".delete_msg", function() {
            //     var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            //     var my_name = $("#active_chat_user_name").val();
            //     var msg_id = $(this).attr('data-id');
            //     var from_id = $(this).attr('data-from');
            //     var to_id = $(this).attr('data-to');
            //     data = {
            //         _token: CSRF_TOKEN,
            //         message_id: msg_id
            //     }
            //     $.ajax({
            //         url: '/api/chats/deleteMsg',
            //         method: 'POST',
            //         data: data,
            //         dataType: 'JSON',
            //         success: function(data_success) {
            //             console.log(data_success);
            //             var data = {
            //                 _token: CSRF_TOKEN,
            //                 from_user_id: to_id,
            //                 my_user_id: from_id
            //             }
            //             $('.direct-chat-messages').html("");
                        
            //             $.ajax({
            //                 url: '/api/chats/loadAllMsg',
            //                 method: 'POST',
            //                 data: data,
            //                 dataType: 'JSON',
            //                 success: function(seen_message) {
            //                     newMessage(data)
            //                     console.log("Hello", from_id);
            //                     for (var i = 0; i < seen_message
            //                         .length; i++) {
            //                         if (seen_message[i].user_from == from_id) {
            //                             var my_msg =
            //                                 '<div class="direct-chat-msg right"  style="margin-right:45px;"><div class="direct-chat-infos clearfix"><span class="direct-chat-name float-right">' +
            //                                 main_name +
            //                                 '</span></a><span class="direct-chat-timestamp float-left">' +
            //                                 seen_message[i].created_at +
            //                                 '</span></div><div class="direct-chat-text">' +
            //                                 seen_message[i].messages +
            //                                 '</div></div><button class="btn btn-danger delete_msg" data-id="' +
            //                                 seen_message[i].id +
            //                                 '" data-from="' +
            //                                 seen_message[i].user_from +
            //                                 '" data-to="' +
            //                                 seen_message[i].user_to +
            //                                 '" style="float: right;margin-top: -45px;"><i class="fas fa-trash"></i></button>';
            //                             $('.direct-chat-messages')
            //                                 .append(my_msg);
            //                         } else if (seen_message[i].user_from == to_id) {
            //                             var my_msg =
            //                                 '<div class="direct-chat-msg"  style="margin-right:45px;"><div class="direct-chat-infos clearfix"><span class="direct-chat-name float-right">' +
            //                                 my_name +
            //                                 '</span><span class="direct-chat-timestamp float-left">' +
            //                                 seen_message[i].created_at +
            //                                 '</span></div><div class="direct-chat-text">' +
            //                                 seen_message[i].messages +
            //                                 '</div></div>';
            //                             $('.direct-chat-messages')
            //                                 .append(my_msg);
            //                         }
            //                     }
            //                 }
            //             })
            //         }
            //     })
            // })
        }
        
        $('tr#ClickableRow').click(function() {
            $('.direct-chat-messages').html("");
            var td = $(this).find('td').eq(2).text();
            var my_name = $(this).find('td').eq(0).text();
            sender_user_id = td;
            $("#active_chat_user_id").val(sender_user_id);
            $("#count_" + sender_user_id).html("");
            
            // active_chat_user_name = $("#active_chat_user_name").val()
            // sender_user_id = $("#active_chat_user_id").val()


            loadMsg()

            $(document).on("click", ".delete_msg", function() {
                $('.direct-chat-messages').html("");
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                var msg_id = $(this).attr('data-id');
                var from_id = $(this).attr('data-from');
                var to_id = $(this).attr('data-to');
                data = {
                    _token: CSRF_TOKEN,
                    message_id: msg_id
                }
                $.ajax({
                    url: '/api/chats/deleteMsg',
                    method: 'POST',
                    data: data,
                    dataType: 'JSON',
                    success: function(data_success) {
                        console.log(data_success);
                        var data = {
                            _token: CSRF_TOKEN,
                            from_user_id: to_id,
                            my_user_id: from_id
                        }
                        $.ajax({
                            url: '/api/chats/loadAllMsg',
                            method: 'POST',
                            data: data,
                            dataType: 'JSON',
                            success: function(seen_message) {
                                console.log("Hello", from_id);
                                newMessage(data)
                                for (var i = 0; i < seen_message
                                    .length; i++) {
                                    if (seen_message[i].user_from == from_id) {
                                        var my_msg =
                                            '<div class="direct-chat-msg right"  style="margin-right:45px;"><div class="direct-chat-infos clearfix"><span class="direct-chat-name float-right">' +
                                            main_name +
                                            '</span></a><span class="direct-chat-timestamp float-left">' +
                                            seen_message[i].created_at +
                                            '</span></div><div class="direct-chat-text">' +
                                            seen_message[i].messages +
                                            '</div></div><button class="btn btn-danger delete_msg" data-id="' +
                                            seen_message[i].id +
                                            '" data-from="' +
                                            seen_message[i].user_from +
                                            '" data-to="' +
                                            seen_message[i].user_to +
                                            '" style="float: right;margin-top: -45px;"><i class="fas fa-trash"></i></button>';
                                        $('.direct-chat-messages')
                                            .append(my_msg);
                                    } else if (seen_message[i].user_from == to_id) {
                                        var my_msg =
                                            '<div class="direct-chat-msg"  style="margin-right:45px;"><div class="direct-chat-infos clearfix"><span class="direct-chat-name float-right">' +
                                            my_name +
                                            '</span><span class="direct-chat-timestamp float-left">' +
                                            seen_message[i].created_at +
                                            '</span></div><div class="direct-chat-text">' +
                                            seen_message[i].messages +
                                            '</div></div>';
                                        $('.direct-chat-messages')
                                            .append(my_msg);
                                    }
                                }
                            }
                        })
                    }
                })
            })

        });

        function newMessage(data) {
            $.ajax({
                url: '/api/chats/showMessges',
                method: 'POST',
                data: data,
                dataType: 'JSON',
                success: function(data_new) {
                    // $('.direct-chat-messages').html("");
                    for (var i = 0; i < data_new.length; i++) {
                        var my_msg =
                            '<div class="direct-chat-msg"  style="margin-right:45px;"><div class="direct-chat-infos clearfix"><span class="direct-chat-name float-right">' +
                            data_new[i].user_from +
                            '</span><span class="direct-chat-timestamp float-left">' +
                            data_new[i].created_at +
                            '</span></div><div class="direct-chat-text">' + data_new[i]
                            .messages +
                            '</div></div>';
                        $('.direct-chat-messages').append(my_msg);
                    }
                    var new_data = {
                        from_user_id: data.my_user_id,
                        my_user_id: data.from_user_id
                    };
                    $.ajax({
                        url: '/api/chats/readMsg',
                        method: 'POST',
                        data: new_data,
                        dataType: 'JSON',
                        success: function(new_data) {
                            // console.log("seen");
                        }
                    })
                }
            })
        }
        $("#chat_form").on("click", function() {
            var date = new Date().toJSON().slice(0, 10);
            var message = $('#text-message').val();
            $('#text-message').val("");
            var from_user = $('#from_user').val();
            var from_user_id = $('#from_user_id').val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var data = {
                _token: CSRF_TOKEN,
                message: message,
                from_user: from_user,
                from_user_id: from_user_id,
                sender_user_id: sender_user_id,
            }
            $.ajax({
                url: '/api/chats/storeHistory',
                method: 'POST',
                data: data,
                dataType: 'JSON',
                success: function(data) {
                    var my_msg =
                        '<div class="direct-chat-msg right"  style="margin-right:45px;"><div class="direct-chat-infos clearfix"><span class="direct-chat-name float-right">' +
                        from_user +
                        '</span><span class="direct-chat-timestamp float-left">' + date +
                        '</span></div><div class="direct-chat-text">' + data.myData
                        .messages +
                        '</div></div><button class="btn btn-danger delete_msg" data-id="' +
                        data.myData.id + '" data-from="' +
                        data.myData.user_from +
                        '" data-to="' + data.myData.user_to +
                        '" style="float: right;margin-top: -45px;"><i class="fas fa-trash"></i></button>';
                    $('.direct-chat-messages').append(my_msg);
                }
            })
        });

        var interval = 2000;

        function doAjax() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var from_to_user_id = $('#from_user_id').val();

            $.ajax({
                type: 'POST',
                url: '/api/chats/countMessage',
                data: {
                    _token: CSRF_TOKEN,
                    from_to_user_id: from_to_user_id
                },
                dataType: 'json',
                success: function(data) {
                    for (var i = 0; i < data.length; i++) {
                        var item = data[i];
                        var count = item.message_count;
                        $("#count_"+item.user_from).html('<span class="right badge badge-danger">' + count +
                                    '</span>');
                    }
                },
                complete: function(data) {
                    setTimeout(doAjax, interval);
                }
            });
        }
        setTimeout(doAjax, interval);
    });
</script>
