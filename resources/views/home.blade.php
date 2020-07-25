
@extends('layouts.app')

@section('content')
    <div id="app" class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @if(Session::has('message'))
                    <div class="alert alert-success">
                        {{session('message')}}
                    </div>
                @endif
                <div class="card">
                    @if (Auth::user()->id == 1)
                        <div class="card-header">Send push to Users</div>

                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>
                                                <form action="{{ route('send-push') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$user->id}}" />

                                                    <input class="btn btn-primary" type="submit" value="Send Push">
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="card-header">User Panel</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.15.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.15.0/firebase-messaging.js"></script>
    <script>
        $(document).ready(function(){
            const firebaseConfig = {
                apiKey: '{{env('FIREBASE_API_KEY')}}',
                authDomain: '{{env('FIREBASE_AUTH_DOMAIN')}}',
                databaseURL:'{{env('FIREBASE_DATABASE_URL')}}',
                projectId: '{{env('FIREBASE_PROJECT_ID')}}',
                storageBucket: '{{env('FIREBASE_STORAGE_BUCKET')}}',
                messagingSenderId: '{{env('FIREBASE_MESSAGING_SENDER_ID')}}',
                appId: '{{env('FIREBASE_APP_ID')}}',
            };
            firebase.initializeApp(firebaseConfig);
            const messaging = firebase.messaging();

            messaging
                .requestPermission()
                .then(function () {
                    return messaging.getToken()
                })
                .then(function(token) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ URL::to('/save-device-token') }}',
                        type: 'POST',
                        data: {
                            user_id: {!! json_encode($user_id ?? '') !!},
                            fcm_token: token,
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            console.log(response)
                        },
                        error: function (err) {
                            console.log(" Cannot send data because: " + JSON.stringify(err));
                        },
                    });
                })
                .catch(function (err) {
                    console.log("Unable to get permission to notify.", err);
                });
        
            messaging.onMessage(function(payload) {
                const noteTitle = payload.notification.title;
                const noteOptions = {
                    body: payload.notification.body,
                    icon: payload.notification.icon,
                };
                new Notification(noteTitle, noteOptions);
            });
        });
    </script>
@endsection