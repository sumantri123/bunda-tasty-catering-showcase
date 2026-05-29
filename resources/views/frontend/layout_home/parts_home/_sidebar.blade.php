<div class="sidebar-header">
    <div>
        <img src="{{ URL::asset(session("logoHeaderTransaksi")) }}" style="width:60px;" alt="logo icon">   
    </div>
    <div>
        <h4 class="logo-text">SIA</h4>
    </div>
    <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
    </div>
</div>
<!--navigation-->
<ul class="metismenu" id="menu">
	<li class="menu-label">Menu</li>
	
	@foreach(App\Helpers\SiteHelpers::main_menu() as $mm)
		
		<li>
			<a href="javascript:;" data-href="{{($mm->submenu_link == null) ? null : $mm->submenu_link}}" class="{{($mm->submenu_link == null) ? 'has-arrow' : null}}">
				<div class="parent-icon"><i class='{{$mm->submenu_icon}}'></i></div>
				<div class="menu-title">{{$mm->submenu_nama}}</div>						
			</a>	
			
			@if ($mm->submenu_link == null)			
				<ul>
					@foreach(App\Helpers\SiteHelpers::side_menu($mm->submenu_parent) as $sm)  
						<li> 
							<a class="{{($sm->submenu_link == null) ? 'has-arrow' : 'action'}}" data-href="{{ ($sm->submenu_param_1 == null) ? ($sm->submenu_link) : $sm->submenu_link.base64_encode($sm->submenu_param_1) }}">
								<i class="{{$sm->submenu_icon}}"></i>
								{{$sm->submenu_nama}}
							</a>
							
							@if ($mm->submenu_link == null)	
								<ul>
									@foreach(App\Helpers\SiteHelpers::side_menu_2($sm->submenu_id) as $sm2)  
										<li> 
											<a class="{{($sm2->submenu_link == null) ? 'has-arrow' : 'action'}}" data-href="{{ ($sm2->submenu_param_1 == null) ? ($sm2->submenu_link) : $sm2->submenu_link.base64_encode($sm2->submenu_param_1) }}">
												<i class="{{$sm2->submenu_icon}}"></i>
												{{$sm2->submenu_nama}}
											</a>
											
											@if ($sm2->submenu_link == null)	
												<ul>
													@foreach(App\Helpers\SiteHelpers::side_menu_3($sm2->submenu_id) as $sm3)  
														<li> 
															<a class="{{($sm3->submenu_link == null) ? 'has-arrow' : 'action'}}" data-href="{{$sm3->submenu_link}}">
																<i class="{{$sm3->submenu_icon}}"></i>
																{{$sm3->submenu_nama}}
															</a>
														</li>
													@endforeach
												</ul>
											@endif
										</li>
									@endforeach	
								</ul>
							@endif
						</li>
						
					@endforeach					
				</ul>	
			@endif			
		</li>
		
	@endforeach
	
</ul>
<!--end navigation-->
