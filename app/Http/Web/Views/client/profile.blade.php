@extends('layouts.web.main_layout')
@section('content')
<section id="page_content" class="profile_page">
   <div class="container">
   <div class="profile_info">
   	<div class="col-md-3">
   		<div class="profile_pic">
   			<div class="profile_pic_img">
   				<img src="{{ config('constants.LOGO') }}" alt="">
   			</div>
   		</div>
   	</div>
   	<div class="col-md-9">
   		<div class="profile_detail">
   			<div class="user_info_block">
   				<h2>Jeremy Rose</h2>
   			</div>
   			<div class="user_info_block">
   				<div class="user_info_block_group_title">
   					<label>Contact Information</label>
	   				<div class="edit_icon">
						<a href="#"><i class="fa fa-pencil"></i></a>
					</div>
   				</div>
   				<div class="user_info_block_group">
   					<span class="col-md-2"><i class="fa fa-phone"></i>Phone:</span>
   					<p class="col-md-8">+1 123 456 7890</p>
   				</div>
   				<div class="user_info_block_group">
   					<span class="col-md-2"><i class="fa fa-map-marker"></i>Address:</span>
   					<p class="col-md-3">525 E 68th Street
New York, NY 10651-78 156-187-60</p>
   				</div>
   				<div class="user_info_block_group">
   					<span class="col-md-2"><i class="fa fa-envelope"></i>Email:</span>
   					<p class="col-md-8">ars@test.com</p>
   				</div>
   			</div>
   			<!-- <div class="sitecount">
   				<div class="col-md-2">
   					<div class="countno">
	   					<span>Chain</span>
	   					<p>25</p>
   					</div>
   				</div>
   				<div class="col-md-2">
   					<div class="countno">
	   					<span>site</span>
	   					<p>100</p>
   					</div>
   				</div>
   			</div> -->
   			<div class="save-btn">
   				<a href="#">Save</a>
   			</div>
   		</div>
   	</div>
   </div>
   </div>
   <!--Tab Content-->
</div>
</div>
@stop

