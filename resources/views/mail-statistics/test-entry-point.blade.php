@extends('layouts.app')

@section('contentheader_title')
    Mail log
@endsection
@section('contentheader_description')
    Display a list of mail log information
@endsection

@section('main-content')
    <a href="javascript:action()">boom</a>
    <script>

        function action() {
            jQuery.ajax({
                url: "{{ url("mail-send") }}",
                type: "POST",
                data: {
                    to: {
                        name: "Stijn Tilleman",
                        email: "stijn@bitsoflove.be"
                    },
                    from: {
                        name: "Stijn Tilleman",
                        email: "stijn@bitsoflove.be"
                    },
                    project: "bitsoflove",
                    subject: "insert subject here",
                    messageData: {
                        view: "emails.test",
                        variables: {
                            service: "mailgun"
                        }
                    }
                },
                success: function (result) {
                    console.log(result);
                }
            })
        }


    </script>



@endsection