@extends('layouts.web.main_layout')
@section('content')
<section class="content">
   <div class="row">
      <div class="col-md-4 col-sm-6 col-xs-12">
         <div class="col-md-2">
            <span class="info-box-icon bg-blue">
               <img src="{{ asset('public/assets/web/img/edit.svg') }}">
            </span>
         </div>
         <div class="col-md-10">
            <div class="info-box">
               <div class="info-box-content">
                  <span class="info-box-text">{{ trans('messages.assignments') }}</span>
                  <span class="info-box-detail">
                     <span>{{ trans('messages.assignment_status.new') }}</span>
                     <span class="info-box-number">
                        {{ $nScheduledAassignments }}
                     </span>
                  </span>
                  <span class="info-box-detail">
                     <span>{{ trans('messages.assignment_status.not_approved') }}</span>
                     <span class="info-box-number">
                        {{ $nPartialAassignments }}
                     </span>
                  </span>
               </div>
            </div>
         </div>
      </div>

       <div class="col-md-4 col-sm-6 col-xs-12">
         <div class="col-md-2">
            <span class="info-box-icon bg-blue">
               <img src="{{ asset('public/assets/web/img/checklist.svg') }}">
            </span>
         </div>
         <div class="col-md-10">
            <div class="info-box">
               <div class="info-box-content">
                  <span class="info-box-text">{{ trans('messages.offers') }}</span>
                  <span class="info-box-detail">
                     <span>{{ trans('messages.new') }}</span>
                     <span class="info-box-number">
                        {{ $nOffers }}
                     </span>
                  </span>
                  {{-- <span class="info-box-detail">
                     <span>{{ trans('messages.close') }}</span>
                     <span class="info-box-number">
                        {{ $nOffers }}
                     </span>
                  </span> --}}
               </div>
            </div>
         </div>
      </div>
   </div>

   <!--TopBox-->
   {{-- <div class="survey-content">
      <div class="col-md-4">
         <div class="survey-content-detail-block-icon">
            <img src="{{ asset('public/assets/web/img/edit.svg') }}">
         </div>
         <div class="survey-content-detail">
            <h2>assignments</h2>
            <div class="survey-content-detail-block">
               <span>Schedule</span>
               <p>{{ $nScheduledAssignmentCount }}</p>
            </div>
            <div class="survey-content-detail-block">
               <span>Completed</span>
               <p>{{ $nCompletedAssignmentCount }}</p>
            </div>
         </div>
      </div>

      <div class="col-md-4">
         <div class="survey-content-detail-block-icon">
            <img src="{{ asset('public/assets/web/img/checklist.svg') }}">
         </div>
         <div class="survey-content-detail">
            <h2>sites</h2>
            <div class="survey-content-detail-block">
               <span>Open</span>
               <p>{{ $nSiteCount }}</p>
            </div>
            <div class="survey-content-detail-block">
               <span>Close</span>
               <p>0</p>
            </div>
         </div>
      </div>

   </div> --}}
   <!--TopBox-->

   <!--Tab Content-->
   <!-- <div id="exTab2" class="container">
      <ul class="nav nav-tabs">
         <li class="active">
            <a href="#1" data-toggle="tab">New<div class="count2">2</div></a>
         </li>
         <li>
            <a href="#2" data-toggle="tab">Partial</a>
         </li>
         <li>
            <a href="#3" data-toggle="tab">Late</a>
         </li>
      </ul>
      <div class="tab-content ">
         <div class="tab-pane active" id="1">
            <div class="content_box_body">
               <div class="content_block">
                  <div class="date_block">
                     <p>aug</p>
                     <span>23</span>
                  </div>
                  <div class="project_detail">
                     <h2>2017 Toys R Us Standard Stpre Visits - 8911</h2>
                     <p>20170724 TRU</p>
                     <span>toys r us 8911, columbus, oh 43240</span>
                  </div>
                  <div class="project_detail_date">
                     <span>01 Aug 2017 11:40 PM - 08 Aug 2017 09:29 AM</span>
                  </div>
               </div>
               <div class="content_block">
                  <div class="date_block">
                     <p>oct</p>
                     <span>20</span>
                  </div>
                  <div class="project_detail">
                     <h2>2017 Toys R Us Standard Stpre Visits - 8911</h2>
                     <p>20170724 TRU</p>
                     <span>toys r us 8911, columbus, oh 43240</span>
                  </div>
                  <div class="project_detail_date">
                     <span>01 Aug 2017 11:40 PM - 08 Aug 2017 09:29 AM</span>
                  </div>
               </div>
               <div class="content_block">
                  <div class="date_block">
                     <p>sep</p>
                     <span>19</span>
                  </div>
                  <div class="project_detail">
                     <h2>2017 Toys R Us Standard Stpre Visits - 8911</h2>
                     <p>20170724 TRU</p>
                     <span>toys r us 8911, columbus, oh 43240</span>
                  </div>
                  <div class="project_detail_date">
                     <span>01 Aug 2017 11:40 PM - 08 Aug 2017 09:29 AM</span>
                  </div>
               </div>
               <div class="content_block">
                  <div class="date_block">
                     <p>aug</p>
                     <span>23</span>
                  </div>
                  <div class="project_detail">
                     <h2>2017 Toys R Us Standard Stpre Visits - 8911</h2>
                     <p>20170724 TRU</p>
                     <span>toys r us 8911, columbus, oh 43240</span>
                  </div>
                  <div class="project_detail_date">
                     <span>01 Aug 2017 11:40 PM - 08 Aug 2017 09:29 AM</span>
                  </div>
               </div>
               <div class="content_block">
                  <div class="date_block">
                     <p>aug</p>
                     <span>23</span>
                  </div>
                  <div class="project_detail">
                     <h2>2017 Toys R Us Standard Stpre Visits - 8911</h2>
                     <p>20170724 TRU</p>
                     <span>toys r us 8911, columbus, oh 43240</span>
                  </div>
                  <div class="project_detail_date">
                     <span>01 Aug 2017 11:40 PM - 08 Aug 2017 09:29 AM</span>
                  </div>
               </div>
            </div>
         </div>
         <div class="tab-pane" id="2">
            <div class="content_box_body">
               <div class="content_block">
                  <div class="date_block">
                     <p>aug</p>
                     <span>23</span>
                  </div>
                  <div class="project_detail">
                     <h2>2017 Toys R Us Standard Stpre Visits - 8911</h2>
                     <p>20170724 TRU</p>
                     <span>toys r us 8911, columbus, oh 43240</span>
                  </div>
                  <div class="project_detail_date">
                     <span>01 Aug 2017 11:40 PM - 08 Aug 2017 09:29 AM</span>
                  </div>
               </div>
               <div class="content_block">
                  <div class="date_block">
                     <p>oct</p>
                     <span>20</span>
                  </div>
                  <div class="project_detail">
                     <h2>2017 Toys R Us Standard Stpre Visits - 8911</h2>
                     <p>20170724 TRU</p>
                     <span>toys r us 8911, columbus, oh 43240</span>
                  </div>
                  <div class="project_detail_date">
                     <span>01 Aug 2017 11:40 PM - 08 Aug 2017 09:29 AM</span>
                  </div>
               </div>
               <div class="content_block">
                  <div class="date_block">
                     <p>sep</p>
                     <span>19</span>
                  </div>
                  <div class="project_detail">
                     <h2>2017 Toys R Us Standard Stpre Visits - 8911</h2>
                     <p>20170724 TRU</p>
                     <span>toys r us 8911, columbus, oh 43240</span>
                  </div>
                  <div class="project_detail_date">
                     <span>01 Aug 2017 11:40 PM - 08 Aug 2017 09:29 AM</span>
                  </div>
               </div>
            </div>
         </div>
         <div class="tab-pane" id="3">
            <div class="content_box_body">
               <div class="content_block">
                  <div class="date_block">
                     <p>aug</p>
                     <span>23</span>
                  </div>
                  <div class="project_detail">
                     <h2>2017 Toys R Us Standard Stpre Visits - 8911</h2>
                     <p>20170724 TRU</p>
                     <span>toys r us 8911, columbus, oh 43240</span>
                  </div>
                  <div class="project_detail_date">
                     <span>01 Aug 2017 11:40 PM - 08 Aug 2017 09:29 AM</span>
                  </div>
               </div>
               <div class="content_block">
                  <div class="date_block">
                     <p>oct</p>
                     <span>20</span>
                  </div>
                  <div class="project_detail">
                     <h2>2017 Toys R Us Standard Stpre Visits - 8911</h2>
                     <p>20170724 TRU</p>
                     <span>toys r us 8911, columbus, oh 43240</span>
                  </div>
                  <div class="project_detail_date">
                     <span>01 Aug 2017 11:40 PM - 08 Aug 2017 09:29 AM</span>
                  </div>
               </div>
            </div>
         </div>
		 <div class="Pagination">
		 	<nav aria-label="Page navigation example">
			  <ul class="pagination">
			    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
			    <li class="page-item"><a class="page-link" href="#">1</a></li>
			    <li class="page-item"><a class="page-link" href="#">2</a></li>
			    <li class="page-item"><a class="page-link" href="#">3</a></li>
			    <li class="page-item"><a class="page-link" href="#">Next</a></li>
			  </ul>
			</nav>
		 </div>
      </div>
   </div> -->
   <!--Tab Content-->
</div>
</div>
@stop