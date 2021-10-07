<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Collection;

use App\Http\Requests;

use App\Assignment;

use App\Payment;

use Datatables;

use DB;

class PaymentController extends Controller
{
	public function store(Request $request){
		$assignment_id = $request->input('assignment_id');
		$this->validate($request, 
			[
				'payment_type'		=> 	'required',
				'qty'				=> 	'required|numeric',
				'rep_payment_type'	=> 	'required',
				'pay_rate'			=> 	'required_if:rep_payment_type,manual',
				'pay_type'			=> 	'required_if:rep_payment_type,manual',
			],[
				'qty.required'				=>	'The Quantity field is required.',
				'qty.numeric'				=>	'The Quantity must be a number.',
				'pay_rate.reuqired'			=>	'The Rep Payment field is required.',
				'pay_type.reuqired'			=>	'The Payment Type field is required.',
			]);


		if($request->input('payment_id') == '' || $request->input('payment_id') == '0')
		{
			$this->validate($request,
				[
					'payment_type'		=> 	'unique_with:assignments_payments,assignment_id=>'.$assignment_id
				],[
					'payment_type.unique_with'	=>	'Payment Type already exist',
				]);
			$payment = new Payment($request->except(['_token']));
			$payment->save();
			
			return response()->json(array(
				"status" => "success",
				"success"=>"Payment saved successfully!",
				)); 
		}else{
			$payment = Payment::where(['id'=>$request->input('payment_id')])->first(); 

			$payment->update($request->except(['_token']));

			return response()->json(array(
				"status" => "success",
				"message"=>"Payment Updated Successfully",
			));
		}
	}

	public function edit($payment_id){
		$inputs = Payment::find($payment_id);
		$filteredArr = [
		'payment_id'=>["type"=>"hidden",'value'=>$inputs->id],
		'payment_type'=>["type"=>"select",'value'=>$inputs->payment_type],
		'qty'=>["type"=>"number",'value'=>$inputs->qty],
		'rep_payment_type'=>["type"=>"select",'value'=>$inputs->rep_payment_type],
		'pay_rate'=>["type"=>"text",'value'=>$inputs->pay_rate],
		'pay_type'=>["type"=>"select",'value'=>$inputs->pay_type],
		];
		return response()->json(array(
			"status" => "success",
			"inputs"=>$filteredArr,
			));
	}

	public function destroy(Request $request){

		$payment = Payment::find($request->input('id'));

		$payment->delete();
		return response()->json(array(
			"status" => "success",
			"message"=>"Payment Removed Successfully",
			));   
	}

	public function getdata(Request $request,$assignment_id){

		$assignment = Assignment::find($assignment_id);

		$payments = $assignment->payments;

		$datatable =  Datatables::of($payments)
		->addColumn('action', function ($payments){
			$html = '';			
			$html .= '<button class="btn btn-box-tool" type="button" name="remove_payment" data-id="'.$payments->id.'" value="delete" title="delete"><span class="fa fa-trash"></span></button>';
			return $html;			
		})
		->addColumn('total', function ($payments) {
			if($payments->pay_rate != '' || $payments->pay_rate != 0){
				return $payments->qty * $payments->pay_rate;
			}
			return 0.00;
		})
		->editColumn('qty', function ($payments) {
			if($payments->qty == 0)
			{
				return '';
			}
			return $payments->qty;
		})
		->editColumn('payment_type', function ($payments) {
			$data = DB::table('_list')->where('id','=',$payments->payment_type)->first();
			$html = '';			
			$html .= '<a href="javascript:void(0)" onClick="setPaymentEdit(this,event)" data-id="'.$payments->id.'">'.$data->item_name.'</a>';
			return $html;	
			
		})
		->editColumn('pay_rate', function ($payments) {
			$html = "";
			if($payments->pay_rate != '' || $payments->pay_rate != 0){
				$html .= $payments->pay_rate.'/'.strtoupper($payments->pay_type);
				return $html;
			}
			return 0.00;
		})
		->make(true);
		return $datatable;
	}
}
