
@if(isset ($errors) && count($errors) > 0)
    @section('scripts')
    <script type="text/javascript">
        var handleMessagesGritterNotification = function() {
            setTimeout(function() {
            
    // <div class="alert alert-danger alert-notification">
    //     <ul class="list-unstyled mb-0">
            @foreach($errors->all() as $error)
                // <li>{{ $error }}</li>
                $.gritter.add({
                    title: '{{ $error }}',
                    text: '{{ $error }}',
                    image: '../assets/img/moods/robot-unhappy.svg',
                    sticky: true,
                    time: 8000,
                    class_name: 'my-sticky-class alert-notification',
                });
            @endforeach
    //     </ul>
    // </div>
            }, 200);
        };

    </script>
    @stop
@endif


@if(Session::get('success', false))
    <?php $data = Session::get('success'); ?>
    @if (is_array($data))
        @foreach ($data as $msg)
            <div class="alert alert-green alert-notification">
                <i class="fa fa-check"></i>
                {{ $msg }}
            </div>
        @endforeach
    @else
        <div class="alert alert-green alert-notification">
            <i class="fa fa-check"></i>
            {{ $data }}
        </div>
    @endif


    @section('scripts')
    <script type="text/javascript">
        var handleMessagesGritterNotification = function() {                        
            setTimeout(function() {                
                $.gritter.add({
                    title: '{{ $data }}',
                    text: '{{ $data }}',
                    image: '../assets/img/moods/robot-happy.svg',
                    sticky: true,
                    time: '',
                    class_name: 'my-sticky-class alert-notification'
                });                
            }, 400);
            // setTimeout(function() {                
            //     var pathname = window.location.pathname;
            //     if( pathname.includes("news")){
            //         window.location.reload(true);
            //     }                  
            // }, 2000);

        };
        

        

             
    </script>
    @stop
@endif
