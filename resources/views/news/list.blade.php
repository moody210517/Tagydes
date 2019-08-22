@extends('layouts.app')

@section('page-title', trans('app.customers'))
@section('page-heading', trans('app.customers'))

@section('content')
@include('partials.messages')

<div class="panel panel-inverse">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <!-- <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a> -->
        </div>
        <h4 class="panel-title">
            @lang('app.list')
        </h4>
    </div>
    <!-- end panel-heading -->
    <div class="panel-body">


        @if(auth()->user()->getRole() == 1)
        <div class="row my-3 flex-md-row flex-column-reverse">
            <div class="col-md-12">
                <a href="{{ route('news.create') }}" class="btn btn-white btn-rounded float-right">
                    <i class="fas fa-plus mr-2"></i>
                    Add News
                </a>
            </div>
        </div>
        @endif
                 
        <div class="table-responsive" id="customers-table-wrapper">            
            <table id="data-table-responsive" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <!-- @lang('app.company_name') -->
                        <th class="min-width-80 text-nowrap"> Image </th>
                        
                        @if(auth()->user()->getRole() == 1)
                        <th class="text-center min-width-250">@lang('app.action')</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @if (count($news))
                    <?php $i=0; ?>
                    @foreach ($news as $new)
                        <tr >
                        
                        <td class="align-middle" onclick="modal({{$i}});" style="cursor: pointer;"> 

                        <div class="media-sm">                                                   
                            <a class="media-left" href="javascript:;">
                            @if($new->image != null)
                                <img src="<?php if($new->image){echo $new->image;}else{echo "uploads/face.png";} ?>" style="width:100px;height:70px;"/>
                            @elseif($new->video)
                            <iframe src="{{$new->video}}" width="100p" height="70" frameborder="0" allowfullscreen></iframe>                            
                            @else

                            @endif
                            </a>
                                    
                            <div class="media-body" style="padding-left:20px;">
                                <h5 class="media-heading">{{$new->title}}</h5>
                                <p>{{$new->description}}</p>
                            </div>
                        </div>
                                
                        
                        </td>
              
                        @if(auth()->user()->getRole() == 1)
                        <td class="align-middle">                            
                            <a href="{{ route('news.edit', $new->id) }}" class="btn btn-icon edit" title="@lang('app.edit_customer')" data-toggle="tooltip" data-placement="top">
                                <i class="fas fa-edit"></i>
                            </a>

                            <a href="{{ route('news.delete', $new->id) }}" class="btn btn-icon" title="@lang('app.delete_customer')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('app.please_confirm')" data-confirm-text="@lang('app.are_you_sure_delete_customer')" data-confirm-delete="@lang('app.yes_delete_him')">
                                <i class="fas fa-trash"></i>
                            </a>
                            <!-- <a  class="btn btn-icon" title="@lang('app.delete_customer')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('app.please_confirm')" data-confirm-text="@lang('app.are_you_sure_delete_customer')" data-confirm-delete="@lang('app.yes_delete_him')">
                                More
                            </a> -->
                        </td>

                        @endif
                        
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="7"><em>@lang('app.no_records_found')</em></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>


<div id="m" class="modal">
  <!-- Modal content -->
  <div class="modal-contentt">    
    <span class="closeIcon">&times;</span>        
    <div class="modal-header" style="border-bottom-color:#fff;">  
      <span id="title" data-preserve-html-node="true" style="font-size: 22pt;">Ari Paul</span>      
    </div>
    
    <div class="modal-body">
      <div class="row" style="backgrond:#333333;">         
      </div>
      <div class="row">
        <div class="col-md-5 col-12">
            <img id="speaker_image" src="" style="width:100%; padding:20px;" alt="Avatar">
            <iframe id="video" src="" width="100%" height="100%" frameborder="0" allowfullscreen style="min-height: 350px"></iframe>

        </div>
        <div class="col-md-7">
            <span id="speaker_description" style="font-color:#d3d3d3;font-size:12pt;overflow:auto;word-wrap:break-word;">
            More info coming soon.
            </span>
        </div>
        
      </div>
   

    </div>
    <div class="modal-footer">
        <div class="col-md-12">
            <button type="button" onclick="closeDialog(this);" style="float:right;" class="btn btn-primary">
                Close
            </button>
        </div>

    </div>
  </div>
</div>

{!! $news->render() !!}

@stop

@section('scripts')
<script>
    
    $("#status").change(function () {
        $("#customers-form").submit();
    });

    $(document).ready(function() {     
       
    });
    
    // get the close icon instance
    var span = document.getElementsByClassName("closeIcon")[0];
        span.onclick = function() {
        // when click close icon, close the modal .
        var modal = document.getElementById("m");
        modal.style.display = "none";
        document.getElementById("video").src='';
    }
          
    window.onclick = function(event) {
        // triger this part when tap on the outside of modal.
        var modal = document.getElementById("m");
        if (event.target == modal) {
        // when tap on the outsite of modal, close the modal.        
        modal.style.display = "none";
        document.getElementById("video").src='';
        }
    } 

    function modal(xx){ 
        

        var news = `{{ json_encode($news)}}`;
        news     = news.replace( /&quot;/g, '"' ),
        news = news.replace(/(\r\n|\n|\r)/gm," ");

        //alert(news);
        //alert(news.substring(400,489))
        try {
            var decodedNews = JSON.parse(news);
            title = decodedNews.data[xx].title;
            desc = decodedNews.data[xx].description;
            image = decodedNews.data[xx].image;
            video = decodedNews.data[xx].video;
        }catch(error) {
            alert(error);
        }
        // console.warn(decodedNews);        
        document.getElementById("title").innerHTML=title;
        document.getElementById("speaker_description").innerHTML=desc;
        if(image != null && image != ""){
            document.getElementById("speaker_image").src=image;
            document.getElementById("speaker_image").style.display = 'block';
            document.getElementById("video").style.display = 'none';
        }else if(video != null && video != ""){
            document.getElementById("video").src=video;
            document.getElementById("speaker_image").style.display = 'none';
            document.getElementById("video").style.display = 'block';
        }

        var modal = document.getElementById("m");
        modal.style.display = "block";        
    }
    function closeDialog(e){
        var modal = document.getElementById("m");
        modal.style.display = "none";  
        document.getElementById("video").src='';
        
    }
    
    
</script>
@stop
