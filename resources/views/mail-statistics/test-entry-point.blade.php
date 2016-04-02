@extends('mail-stats::layouts.app')

@section('content')
    <a class="btn btn-primary" href="javascript:action()">Click me to send an email.</a>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
        function action() {
            {{-- add the csrf token to every ajax request --}}
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

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
            });
        }


    </script>
@endsection