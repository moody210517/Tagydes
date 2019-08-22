@extends('layouts.app')

@section('page-title', trans('app.dashboard'))
@section('page-heading', trans('app.dashboard'))

@section('content')







<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
  <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
  <li class="breadcrumb-item active">Dashboard</li>
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">Dashboard <small>header small text goes here...</small></h1>
<!-- end page-header -->

<!-- begin row -->
<div class="row">
  <!-- begin col-3 -->
  <div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-grey-darker">
      <div class="stats-icon"><i class="fa fa-desktop"></i></div>
      <div class="stats-info">
        <h4>TOTAL RESELLERS</h4>
        <p>{{ number_format($stats['resellersCount']) }}</p>
      </div>
      <div class="stats-link">
        <a href="{{ route('reseller.list') }}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
      </div>
    </div>
  </div>
  <!-- end col-3 -->
  <!-- begin col-3 -->
  <div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-green">
      <div class="stats-icon"><i class="fa fa-link"></i></div>
      <div class="stats-info">
        <h4>TOTAL CUSTOMERS</h4>
        <p>{{ number_format($stats['customersCount']) }}</p>   
      </div>
      <div class="stats-link">
        <a href="{{ route('customer.list') }}">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
      </div>
    </div>
  </div>
  <!-- end col-3 -->
  <!-- begin col-3 -->
  <div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-orange">
      <div class="stats-icon"><i class="fa fa-users"></i></div>
      <div class="stats-info">
        <h4>TOTAL SUBSCRIPTIONS</h4>
        <p>{{ number_format($stats['subscriptionsCount']) }}</p>    
      </div>
      <div class="stats-link">
        <a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
      </div>
    </div>
  </div>
  <!-- end col-3 -->
  <!-- begin col-3 -->
  <div class="col-lg-3 col-md-6">
    <div class="widget widget-stats bg-red">
      <div class="stats-icon"><i class="fa fa-clock"></i></div>
      <div class="stats-info">
        <h4>ABOUT TO EXPIRE</h4>
        <p>{{ number_format($stats['aboutExpiredCount']) }}</p> 
      </div>
      <div class="stats-link">
        <a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
      </div>
    </div>
  </div>
  <!-- end col-3 -->
</div>
<!-- end row -->
<!-- begin row -->
<div class="row">
  <!-- begin col-8 -->
  <div class="col-lg-8">
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="index-1">
      <div class="panel-heading">
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
        </div>
        <h4 class="panel-title">Website Analytics (Last 7 Days)</h4>
      </div>
      <div class="panel-body">
        <div id="interactive-chart" class="height-sm"></div>
      </div>
    </div>
    <!-- end panel -->

    <!-- begin tabs -->
    <ul class="nav nav-tabs nav-justified nav-justified-mobile" data-sortable-id="index-2">
      <li class="nav-item"><a href="#latest-post" data-toggle="tab" class="nav-link active"><i class="fa fa-camera fa-lg m-r-5"></i> <span class="d-none d-md-inline">Latest Post</span></a></li>
      <li class="nav-item"><a href="#purchase" data-toggle="tab" class="nav-link"><i class="fa fa-archive fa-lg m-r-5"></i> <span class="d-none d-md-inline">Purchase</span></a></li>
      <li class="nav-item"><a href="#email" data-toggle="tab" class="nav-link"><i class="fa fa-envelope fa-lg m-r-5"></i> <span class="d-none d-md-inline">Email</span></a></li>
    </ul>
    <div class="tab-content" data-sortable-id="index-3">
      <div class="tab-pane fade active show" id="latest-post">
        <div class="height-sm" data-scrollbar="true">
          <ul class="media-list media-list-with-divider">
            
            
          <?php $i = 0 ;?>
          @foreach ($news as $new)
            <li class="media media-lg" onclick="modal({{$i}});" style="cursor: pointer;">
                <a href="javascript:;" class="pull-left">
                  @if($new->image != null)
                      <img  class="media-object" src="<?php if($new->image){echo $new->image;}else{echo "uploads/face.png";} ?>" style="width:200px;height:150px;"/>
                  @elseif($new->video)
                  <iframe src="{{$new->video}}" width="200" height="150" frameborder="0" allowfullscreen></iframe>                            
                  @else
                  @endif
                  
                </a>
                <div class="media-body">
                  <h4 class="media-heading">{{ $new->title }}</h4>       
                  {{ $new->description }}
                  <br>
                 
                </div>

              </li>
            <?php $i++;?>
          @endforeach
            
           


          
          </ul>
        </div>
      </div>
      <div class="tab-pane fade" id="purchase">
        <div class="height-sm" data-scrollbar="true">
          <table class="table">
            <thead>
              <tr>
                <th>Date</th>
                <th class="hidden-sm">Product</th>
                <th></th>
                <th>Amount</th>
                <th>User</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>13/02/2013</td>
                <td class="hidden-sm">
                  <a href="javascript:;">
                    <img src="{{ asset('assets/img/product/product-1.png') }}" alt=""  />
                  </a>
                </td>
                <td class="text-nowrap">
                  <h6><a href="javascript:;">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
                </td>
                <td>$349.00</td>
                <td class="text-nowrap"><a href="javascript:;">Derick Wong</a></td>
              </tr>
              <tr>
                <td>13/02/2013</td>
                <td class="hidden-sm">
                  <a href="javascript:;">
                    <img src="{{ asset('assets/img/product/product-2.png') }}" alt="" />
                  </a>
                </td>
                <td class="text-nowrap">
                  <h6><a href="javascript:;">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
                </td>
                <td>$399.00</td>
                <td class="text-nowrap"><a href="javascript:;">Derick Wong</a></td>
              </tr>
              <tr>
                <td>13/02/2013</td>
                <td class="hidden-sm">
                  <a href="javascript:;">
                    <img src="{{ asset('assets/img/product/product-3.png') }}" alt="" />
                  </a>
                </td>
                <td class="text-nowrap">
                  <h6><a href="javascript:;">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
                </td>
                <td>$499.00</td>
                <td class="text-nowrap"><a href="javascript:;">Derick Wong</a></td>
              </tr>
              <tr>
                <td>13/02/2013</td>
                <td class="hidden-sm">
                  <a href="javascript:;">
                    <img src="{{ asset('assets/img/product/product-4.png') }}" alt="" />
                  </a>
                </td>
                <td class="text-nowrap">
                  <h6><a href="javascript:;">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
                </td>
                <td>$230.00</td>
                <td class="text-nowrap"><a href="javascript:;">Derick Wong</a></td>
              </tr>
              <tr>
                <td>13/02/2013</td>
                <td class="hidden-tablet hidden-phone">
                  <a href="javascript:;">
                    <img src="{{ asset('assets/img/product/product-5.png') }}" alt="" />
                  </a>
                </td>
                <td class="text-nowrap">
                  <h6><a href="javascript:;">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
                </td>
                <td>$500.00</td>
                <td class="text-nowrap"><a href="javascript:;">Derick Wong</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="tab-pane fade" id="email">
        <div class="height-sm" data-scrollbar="true">
          <ul class="media-list media-list-with-divider">
            <li class="media media-sm">
              <a href="javascript:;" class="pull-left">
                <img src="{{ asset('assets/img/user/user-1.jpg') }}" alt="" class="media-object rounded-corner" />
              </a>
              <div class="media-body">
                <a href="javascript:;"><h4 class="media-heading">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h4></a>
                <p class="m-b-5">
                  Aenean mollis arcu sed turpis accumsan dignissim. Etiam vel tortor at risus tristique convallis. Donec adipiscing euismod arcu id euismod. Suspendisse potenti. Aliquam lacinia sapien ac urna placerat, eu interdum mauris viverra.
                </p>
                <i class="text-muted">Received on 04/16/2013, 12.39pm</i>
              </div>
            </li>
            <li class="media media-sm">
              <a href="javascript:;" class="pull-left">
                <img src="{{ asset('assets/img/user/user-2.jpg') }}" alt="" class="media-object rounded-corner" />
              </a>
              <div class="media-body">
                <a href="javascript:;"><h4 class="media-heading">Praesent et sem porta leo tempus tincidunt eleifend et arcu.</h4></a>
                <p class="m-b-5">
                  Proin adipiscing dui nulla. Duis pharetra vel sem ac adipiscing. Vestibulum ut porta leo. Pellentesque orci neque, tempor ornare purus nec, fringilla venenatis elit. Duis at est non nisl dapibus lacinia.
                </p>
                <i class="text-muted">Received on 04/16/2013, 12.39pm</i>
              </div>
            </li>
            <li class="media media-sm">
              <a href="javascript:;" class="pull-left">
                <img src="{{ asset('assets/img/user/user-3.jpg') }}" alt="" class="media-object rounded-corner" />
              </a>
              <div class="media-body">
                <a href="javascript:;"><h4 class="media-heading">Ut mi eros, varius nec mi vel, consectetur convallis diam.</h4></a>
                <p class="m-b-5">
                  Ut mi eros, varius nec mi vel, consectetur convallis diam. Nullam eget hendrerit eros. Duis lacinia condimentum justo at ultrices. Phasellus sapien arcu, fringilla eu pulvinar id, mattis quis mauris.
                </p>
                <i class="text-muted">Received on 04/16/2013, 12.39pm</i>
              </div>
            </li>
            <li class="media media-sm">
              <a href="javascript:;" class="pull-left">
                <img src="{{ asset('assets/img/user/user-4.jpg') }}" alt="" class="media-object rounded-corner" />
              </a>
              <div class="media-body">
                <a href="javascript:;"><h4 class="media-heading">Aliquam nec dolor vel nisl dictum ullamcorper.</h4></a>
                <p class="m-b-5">
                  Aliquam nec dolor vel nisl dictum ullamcorper. Duis vel magna enim. Aenean volutpat a dui vitae pulvinar. Nullam ligula mauris, dictum eu ullamcorper quis, lacinia nec mauris.
                </p>
                <i class="text-muted">Received on 04/16/2013, 12.39pm</i>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!-- end tabs -->

    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="index-4">
      <div class="panel-heading">
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
        </div>
        <h4 class="panel-title">Quick Post</h4>
      </div>
      <div class="panel-toolbar">
        <div class="btn-group m-r-5">
          <a class="btn btn-white" href="javascript:;"><i class="fa fa-bold"></i></a>
          <a class="btn btn-white active" href="javascript:;"><i class="fa fa-italic"></i></a>
          <a class="btn btn-white" href="javascript:;"><i class="fa fa-underline"></i></a>
        </div>
        <div class="btn-group">
          <a class="btn btn-white" href="javascript:;"><i class="fa fa-align-left"></i></a>
          <a class="btn btn-white active" href="javascript:;"><i class="fa fa-align-center"></i></a>
          <a class="btn btn-white" href="javascript:;"><i class="fa fa-align-right"></i></a>
          <a class="btn btn-white" href="javascript:;"><i class="fa fa-align-justify"></i></a>
        </div>
      </div>
      <textarea class="form-control no-rounded-corner" rows="14">Enter some comment.</textarea>
      <div class="panel-footer text-right">
        <a href="javascript:;" class="btn btn-default btn-sm">Cancel</a>
        <a href="javascript:;" class="btn btn-primary btn-sm m-l-5">Action</a>
      </div>
    </div>
    <!-- end panel -->

    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="index-5">
      <div class="panel-heading">
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
        </div>
        <h4 class="panel-title">Message</h4>
      </div>
      <div class="panel-body">
        <div class="height-sm" data-scrollbar="true">
          <ul class="media-list media-list-with-divider media-messaging">
            <li class="media media-sm">
              <a href="javascript:;" class="pull-left">
                <img src="{{ asset('assets/img/user/user-5.jpg') }}" alt="" class="media-object rounded-corner" />
              </a>
              <div class="media-body">
                <h5 class="media-heading">John Doe</h5>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi id nunc non eros fermentum vestibulum ut id felis. Nunc molestie libero eget urna aliquet, vitae laoreet felis ultricies. Fusce sit amet massa malesuada, tincidunt augue vitae, gravida felis.</p>
              </div>
            </li>
            <li class="media media-sm">
              <a href="javascript:;" class="pull-left">
                <img src="{{ asset('assets/img/user/user-6.jpg') }}" alt="" class="media-object rounded-corner" />
              </a>
              <div class="media-body">
                <h5 class="media-heading">Terry Ng</h5>
                <p>Sed in ante vel ipsum tristique euismod posuere eget nulla. Quisque ante sem, scelerisque iaculis interdum quis, eleifend id mi. Fusce congue leo nec mauris malesuada, id scelerisque sapien ultricies.</p>
              </div>
            </li>
            <li class="media media-sm">
              <a href="javascript:;" class="pull-left">
                <img src="{{ asset('assets/img/user/user-8.jpg') }}" alt="" class="media-object rounded-corner" />
              </a>
              <div class="media-body">
                <h5 class="media-heading">Fiona Log</h5>
                <p>Pellentesque dictum in tortor ac blandit. Nulla rutrum eu leo vulputate ornare. Nulla a semper mi, ac lacinia sapien. Sed volutpat ornare eros, vel semper sem sagittis in. Quisque risus ipsum, iaculis quis cursus eu, tristique sed nulla.</p>
              </div>
            </li>
            <li class="media media-sm">
              <a href="javascript:;" class="pull-left">
                <img src="{{ asset('assets/img/user/user-7.jpg') }}" alt="" class="media-object rounded-corner" />
              </a>
              <div class="media-body">
                <h5 class="media-heading">John Doe</h5>
                <p>Morbi molestie lorem quis accumsan elementum. Morbi condimentum nisl iaculis, laoreet risus sed, porta neque. Proin mi leo, dapibus at ligula a, aliquam consectetur metus.</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="panel-footer">
        <form>
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Enter message" />
            <span class="input-group-append">
              <button class="btn btn-primary" type="button"><i class="fa fa-pencil-alt"></i></button>
            </span>
          </div>
        </form>
      </div>
    </div>
    <!-- end panel -->
  </div>
  <!-- end col-8 -->
  <!-- begin col-4 -->
  <div class="col-lg-4">
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="index-6">
      <div class="panel-heading">
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
        </div>
        <h4 class="panel-title">Analytics Details</h4>
      </div>
      <div class="panel-body p-t-0">
        <div class="table-responsive">
          <table class="table table-valign-middle">
            <thead>
              <tr>    
                <th>Source</th>
                <th>Total</th>
                <th>Trend</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><label class="label label-danger">Unique Visitor</label></td>
                <td>13,203 <span class="text-success"><i class="fa fa-arrow-up"></i></span></td>
                <td><div id="sparkline-unique-visitor"></div></td>
              </tr>
              <tr>
                <td><label class="label label-warning">Bounce Rate</label></td>
                <td>28.2%</td>
                <td><div id="sparkline-bounce-rate"></div></td>
              </tr>
              <tr>
                <td><label class="label label-success">Total Page Views</label></td>
                <td>1,230,030</td>
                <td><div id="sparkline-total-page-views"></div></td>
              </tr>
              <tr>
                <td><label class="label label-primary">Avg Time On Site</label></td>
                <td>00:03:45</td>
                <td><div id="sparkline-avg-time-on-site"></div></td>
              </tr>
              <tr>
                <td><label class="label label-default">% New Visits</label></td>
                <td>40.5%</td>
                <td><div id="sparkline-new-visits"></div></td>
              </tr>
              <tr>
                <td><label class="label label-inverse">Return Visitors</label></td>
                <td>73.4%</td>
                <td><div id="sparkline-return-visitors"></div></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- end panel -->

    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="index-7">
      <div class="panel-heading">
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
        </div>
        <h4 class="panel-title">Visitors User Agent</h4>
      </div>
      <div class="panel-body">
        <div id="donut-chart" class="height-sm"></div>
      </div>
    </div>
    <!-- end panel -->

    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="index-8">
      <div class="panel-heading">
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
        </div>
        <h4 class="panel-title">Todo List</h4>
      </div>
      <div class="panel-body p-0">
        <ul class="todolist">
          <li class="active">
            <a href="javascript:;" class="todolist-container active" data-click="todolist">
              <div class="todolist-input"><i class="fa fa-square"></i></div>
              <div class="todolist-title">Donec vehicula pretium nisl, id lacinia nisl tincidunt id.</div>
            </a>
          </li>
          <li>
            <a href="javascript:;" class="todolist-container" data-click="todolist">
              <div class="todolist-input"><i class="fa fa-square"></i></div>
              <div class="todolist-title">Duis a ullamcorper massa.</div>
            </a>
          </li>
          <li>
            <a href="javascript:;" class="todolist-container" data-click="todolist">
              <div class="todolist-input"><i class="fa fa-square"></i></div>
              <div class="todolist-title">Phasellus bibendum, odio nec vestibulum ullamcorper.</div>
            </a>
          </li>
          <li>
            <a href="javascript:;" class="todolist-container" data-click="todolist">
              <div class="todolist-input"><i class="fa fa-square"></i></div>
              <div class="todolist-title">Duis pharetra mi sit amet dictum congue.</div>
            </a>
          </li>
          <li>
            <a href="javascript:;" class="todolist-container" data-click="todolist">
              <div class="todolist-input"><i class="fa fa-square"></i></div>
              <div class="todolist-title">Duis pharetra mi sit amet dictum congue.</div>
            </a>
          </li>
          <li>
            <a href="javascript:;" class="todolist-container" data-click="todolist">
              <div class="todolist-input"><i class="fa fa-square"></i></div>
              <div class="todolist-title">Phasellus bibendum, odio nec vestibulum ullamcorper.</div>
            </a>
          </li>
          <li>
            <a href="javascript:;" class="todolist-container active" data-click="todolist">
              <div class="todolist-input"><i class="fa fa-square"></i></div>
              <div class="todolist-title">Donec vehicula pretium nisl, id lacinia nisl tincidunt id.</div>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <!-- end panel -->

    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="index-9">
      <div class="panel-heading">
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
        </div>
        <h4 class="panel-title">World Visitors</h4>
      </div>
      <div class="panel-body p-0">
        <div id="world-map" class="height-sm width-full"></div>
      </div>
    </div>
    <!-- end panel -->

    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="index-10">
      <div class="panel-heading">
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
        </div>
        <h4 class="panel-title">Calendar</h4>
      </div>
      <div class="panel-body">
        <div id="datepicker-inline" class="datepicker-full-width overflow-y-scroll position-relative"><div></div></div>
      </div>
    </div>
    <!-- end panel -->
  </div>
  <!-- end col-4 -->
</div>
<!-- end row -->


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
        <div class="col-md-5">
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
            <button type="button" onclick="closeDialog();" style="float:right;" class="btn btn-primary">
                Close
            </button>
        </div>

    </div>
  </div>
</div>


@stop

@section('scripts')

{!! HTML::script('assets/plugins/gritter/js/jquery.gritter.js') !!}
{!! HTML::script('assets/plugins/flot/jquery.flot.min.js') !!}
{!! HTML::script('assets/plugins/flot/jquery.flot.time.min.js') !!}
{!! HTML::script('assets/plugins/flot/jquery.flot.resize.min.js') !!}
{!! HTML::script('assets/plugins/flot/jquery.flot.pie.min.js') !!}
{!! HTML::script('assets/plugins/sparkline/jquery.sparkline.js') !!}
{!! HTML::script('assets/plugins/jquery-jvectormap/jquery-jvectormap.min.js') !!}
{!! HTML::script('assets/plugins/jquery-jvectormap/jquery-jvectormap-world-mill-en.js') !!}
{!! HTML::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
{!! HTML::script('assets/js/demo/dashboard.min.js') !!}

<script>
  $(document).ready(function() {
    Dashboard.init();
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
            title = decodedNews[xx].title;
            desc = decodedNews[xx].description;
            image = decodedNews[xx].image;
            video = decodedNews[xx].video;
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
    function closeDialog(){
        var modal = document.getElementById("m");
        modal.style.display = "none";    
        document.getElementById("video").src='';
    }


</script>


@stop