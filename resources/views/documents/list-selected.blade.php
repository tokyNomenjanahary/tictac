<div class="request-bx-1 m-t-2"  id="document_content">
	<div style="width:100%;margin:auto;">
    @if(isset($savedDocuments) && !empty($savedDocuments))
		@foreach($savedDocuments as $savedDocument)
			<a href="javascript:void(0);" class="saved_document">
				<div class="card" style="margin-bottom:10px;margin-left:20px;width:25rem;display:inline-block;">
				  <div style="position:relative;">
						<span class="badge shoppingBadge shoppingBadge-custom" style="background-color:rgb(51,122,183);position:absolute;right:50px;top:0px;">{{$savedDocument->type}}</span>
						<input id="doc-{{$savedDocument->id}}" data-id="{{$savedDocument->id}}" type="checkbox" class="checkDocument" style="display:none;width:20px;height:20px;position:absolute;left:5px;top:0px;"/>
						@php
							$ext = explode('.',$savedDocument->name);
							$ext = $ext[count($ext) - 1 ];
						@endphp
						@if($ext == 'docx' || $ext == 'doc')
							<img class="card-img-top" width="150" height="150;" src="images/docx.jpg" alt="Card image cap">
						@elseif ($ext == 'pdf')
							<img class="card-img-top" width="150" height="150;" src="images/pdf.png" alt="Card image cap">
						@else
							<img class="card-img-top" width="200" height="150;" src="{{URL::asset('uploads/tempfile/' . $savedDocument->name)}}" alt="Card image cap">
						@endif
				 </div>
				  <div class="card-body" style="margin-top:5px;">
					 <h5 class="card-title"></h5>
					 <a target="_blank" href="{{URL::asset('uploads/tempfile/' . $savedDocument->name)}}" class="btn btn-primary"> {{ __('document.view_doc') }}</a>
					 <a  href="javascript:void(0);" data-id="{{$savedDocument->id}}"  class="edit_document btn btn-info" style="display:inline-block;"><i class="fa-solid fa-edit" style="color:white;font-size:15px;"></i></a>
				     <input type="hidden" id="document-type-{{$savedDocument->id}}" value="{{$savedDocument->type}}"/>
					 <input type="hidden" id="document-file-{{$savedDocument->id}}" value="{{URL::asset('uploads/tempfile/' . $savedDocument->name)}}"/>
				  </div>
				</div>
			</a>
		@endforeach
	@endif
	</div>
</div>
