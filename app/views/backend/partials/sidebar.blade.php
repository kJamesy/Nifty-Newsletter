                <div class="media user-media">
                    <a class="user-link" href="{{ URL::to('dashboard/users/profile') }}">
                        <span class="fa fa-user fa-4x img-thumbnail user-img text-danger"></span> 
                    </a> 
                    <div class="media-body">
                        <h5 class="media-heading">{{ $user->first_name . ' ' . $user->last_name }}</h5>
                        <ul class="list-unstyled user-info">
                            <li><span class="label label-danger">{{ $user->getGroups()[0]->name; }}</span></li>
                            <li>You logged in :
                                <br>
                                <small><i class="fa fa-calendar"></i>&nbsp; {{ $logged_in_for }}</small> 
                            </li>
                        </ul>
                    </div>
                </div>

                <ul id="menu" class="collapse">
                    <li class="nav-header">Menu</li>
                    <li class="nav-divider"></li>
                    <li class="{{ $active=='index' ? 'active' : '' }}">
                        <a href="{{ URL::to('dashboard') }}"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a>
                    </li>
                    <li class="{{ $activeParent=='emails' ? 'active' : '' }}">
                        <a href=""><i class="fa fa-envelope-o"></i>&nbsp;Emails <span class="fa arrow"></span> </a> 
                        <ul>
                            <li class="{{ $active=='createemail' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/emails/create') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;New Email
                                </a>
                            </li>
                            <li class="{{ $active=='sentemails' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/emails/sent') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;Sent Emails 
                                </a>
                            </li>
                            <li class="{{ $active=='draftemails' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/emails/drafts') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;Drafts
                                </a>
                            </li>
                            <li class="{{ $active=='trash' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/emails/trash') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;Trash
                                </a>
                            </li>   
                        </ul>
                    </li>
<!--                     <li class="{{ $activeParent=='pages' ? 'active' : '' }}">
                        <a href=""><i class="fa fa-folder-open-o"></i>&nbsp;Pages <span class="fa arrow"></span> </a> 
                        <ul>
                            <li class="{{ $active=='allpages' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/pages') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;All Pages
                                </a>
                            </li>                        
                            <li class="{{ $active=='createpage' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/pages/create') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;New Page
                                </a>
                            </li>
                            <li class="{{ $active=='trash' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/pages/trash') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;Trash
                                </a>
                            </li>   
                        </ul>
                    </li>   -->                    
                    <li class="{{ $activeParent=='tags' ? 'active' : '' }}">
                        <a href=""><i class="fa fa-tags"></i>&nbsp;Tags <span class="fa arrow"></span> </a> 
                        <ul>
                            <li class="{{ $active=='alltags' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/tags') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;All Tags 
                                </a>
                            </li>
                            <li class="{{ $active=='createtag' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/tags/create') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;New Tag
                                </a>
                            </li>
                        </ul>
                    </li>                    
                    <li class="{{ $activeParent=='lists' ? 'active' : '' }}">
                        <a href=""><i class="fa fa-book"></i>&nbsp;Mail Lists <span class="fa arrow"></span> </a> 
                        <ul>
                            <li class="{{ $active=='alllists' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/lists') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;All Mail Lists 
                                </a>
                            </li>
                            <li class="{{ $active=='createlist' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/lists/create') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;New Mail List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ $activeParent=='subscribers' ? 'active' : '' }}">
                        <a href="javascript:;"><i class="fa fa-rss"></i>&nbsp;Subscribers <span class="fa arrow"></span> </a> 
                        <ul>
                            <li class="{{ $active=='allsubscribers' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/subscribers') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;All Subscribers
                                </a>
                            </li>
                            <li class="{{ $active=='createsubscriber' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/subscribers/create') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;New Subscriber
                                </a>
                            </li>
                            <li class="{{ $active=='trash' ? 'active' : '' }}">
                                <a href="{{ URL::to('dashboard/subscribers/trash') }}">
                                    <i class="fa fa-angle-right"></i>&nbsp;Trash
                                </a>
                            </li>                            
                        </ul>
                    </li>
                    <li class="{{ $activeParent=='users' ? 'active' : '' }}">
                        <a href="javascript:;"><i class="fa fa-group"></i>&nbsp;Users <span class="fa arrow"></span> </a> 
                        <ul>
                            @if ( $isAdmin )
                                <li class="{{ $active=='profile' ? 'active' : '' }}">
                                    <a href="{{ URL::to('dashboard/users/profile') }}">
                                        <i class="fa fa-angle-right"></i>&nbsp;Your Profile
                                    </a>
                                </li>
                                <li class="{{ $active=='allusers' ? 'active' : '' }}">
                                    <a href="{{ URL::to('dashboard/users/') }}">
                                        <i class="fa fa-angle-right"></i>&nbsp;All Users 
                                    </a>
                                </li>
                                <li class="{{ $active=='createuser' ? 'active' : '' }}">
                                    <a href="{{ URL::to('dashboard/users/create') }}">
                                        <i class="fa fa-angle-right"></i>&nbsp;New User
                                    </a>
                                </li>
                            @else
                                <li class="{{ $active=='profile' ? 'active' : '' }}">
                                    <a href="{{ URL::to('dashboard/users/profile') }}">
                                        <i class="fa fa-angle-right"></i>&nbsp;Your Profile
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                </ul><!-- /#menu -->