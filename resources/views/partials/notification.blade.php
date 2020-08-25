 <div class="position-relative d-inline-block">
                    <button class="header-icon btn btn-empty" type="button" id="notificationButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="simple-icon-bell"></i>
                        <span class="count" id="count">0</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right mt-3 position-absolute" id="notificationDropdown">
                        <div class="scroll" id="scroll">
                           
                        </div>
						<a id="viewAll" style="display:none" href="{{url('/notifications')}}">View All Notifications</a>
                    </div>
                </div>