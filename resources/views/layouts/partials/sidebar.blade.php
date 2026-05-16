{{-- resources/views/admin/partials/sidebar.blade.php --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="{{ url('/') }}" class="brand-logo text-decoration-none">
            <img src="{{ asset('images/logo-white.png') }}"
                 alt="RSCB"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="brand-fallback" style="display:none; width:45px; height:45px; background:linear-gradient(135deg,#667eea,#764ba2); border-radius:12px; align-items:center; justify-content:center; color:white; font-weight:700; font-size:18px;">RS</div>
            <span class="brand-text">RSCB</span>
        </a>
    </div>

    <div class="sidebar-menu">
        <nav class="menu">
            <ul class="nav flex-column">
                {{-- Dashboard Links --}}
                @role('super_admin')
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @endrole

                @role('regional_sports_secretary')
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.regional.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.regional.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-pie-chart-fill"></i>
                        <span>Regional Dashboard</span>
                    </a>
                </li>
                @endrole

                @role('airport_sports_secretary')
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.airport.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.airport.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-building"></i>
                        <span>Airport Dashboard</span>
                    </a>
                </li>
                @endrole

                {{-- Divider --}}
                <div class="sidebar-divider">
                    <span>Management</span>
                </div>

                {{-- Region Management --}}
                @can('view_regions')
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.regions.index') }}"
                       class="nav-link {{ request()->routeIs('admin.regions.*') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Regions</span>
                    </a>
                </li>
                @endcan

                {{-- Airport Management --}}
                @can('view_airports')
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.airports.index') }}"
                       class="nav-link {{ request()->routeIs('admin.airports.*') ? 'active' : '' }}">
                        <i class="bi bi-airplane-engines-fill"></i>
                        <span>Airports</span>
                    </a>
                </li>
                @endcan

                {{-- Employee Management --}}
                @can('view_employees')
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.employees.index') }}"
                       class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Employees</span>
                    </a>
                </li>
                @endcan

                {{-- Divider --}}
                <div class="sidebar-divider">
                    <span>Events</span>
                </div>

                {{-- Event Management --}}
                @can('view_events')
                <li class="nav-item has-submenu mb-1">
                    <a href="#eventSubmenu"
                       class="nav-link {{ request()->routeIs('admin.events.*') || request()->routeIs('admin.forms.*') ? 'active' : '' }}"
                       data-bs-toggle="collapse"
                       aria-expanded="{{ request()->routeIs('admin.events.*') || request()->routeIs('admin.forms.*') ? 'true' : 'false' }}">
                        <i class="bi bi-calendar-event-fill"></i>
                        <span>Events</span>
                    </a>
                    <ul class="collapse sub-menu {{ request()->routeIs('admin.events.*') || request()->routeIs('admin.forms.*') ? 'show' : '' }}" id="eventSubmenu">
                        <li>
                            <a href="{{ route('admin.events.index') }}"
                               class="nav-link {{ request()->routeIs('admin.events.index') ? 'active' : '' }}">
                                <i class="bi bi-list-ul"></i>
                                <span>All Events</span>
                            </a>
                        </li>
                        @can('create_events')
                        <li>
                            <a href="{{ route('admin.events.create') }}"
                               class="nav-link {{ request()->routeIs('admin.events.create') ? 'active' : '' }}">
                                <i class="bi bi-plus-circle"></i>
                                <span>Create Event</span>
                            </a>
                        </li>
                        @endcan
                        @can('manage_form_templates')
                        <li>
                            <a href="{{ route('admin.forms.templates') }}"
                               class="nav-link {{ request()->routeIs('admin.forms.*') ? 'active' : '' }}">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Form Templates</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                {{-- Registrations --}}
                @can('view_registrations')
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.registrations.index') }}"
                       class="nav-link {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard2-check-fill"></i>
                        <span>Registrations</span>
                        @php
                            $pendingCount = \App\Models\EventRegistration::where('status', 'pending');
                            $user = auth()->guard('employee')->user();
                            if ($user->hasRole('regional_sports_secretary')) {
                                $pendingCount->whereHas('event', function($q) use ($user) {
                                    $q->where('region_id', $user->region_id);
                                });
                            } elseif ($user->hasRole('airport_sports_secretary')) {
                                $pendingCount->whereHas('event', function($q) use ($user) {
                                    $q->where('airport_id', $user->airport_id);
                                });
                            }
                            $pendingCount = $pendingCount->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="badge bg-warning ms-auto">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
                @endcan

                {{-- Divider --}}
                <div class="sidebar-divider">
                    <span>Reports & Media</span>
                </div>

                {{-- Reports --}}
                @can('view_reports')
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.reports.index') }}"
                       class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Reports & Analytics</span>
                    </a>
                </li>
                @endcan

                {{-- Certificates --}}
                @can('generate_certificates')
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.certificates.index') }}"
                       class="nav-link {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                        <i class="bi bi-award-fill"></i>
                        <span>Certificates</span>
                    </a>
                </li>
                @endcan

                {{-- Announcements --}}
                @can('manage_announcements')
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.announcements.index') }}"
                       class="nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                        <i class="bi bi-megaphone-fill"></i>
                        <span>Announcements</span>
                    </a>
                </li>
                @endcan

                {{-- Gallery --}}
                @can('manage_gallery')
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.gallery.index') }}"
                       class="nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                        <i class="bi bi-images"></i>
                        <span>Gallery</span>
                    </a>
                </li>
                @endcan

                {{-- Divider --}}
                @can('manage_settings')
                <div class="sidebar-divider">
                    <span>Settings</span>
                </div>

                <li class="nav-item has-submenu mb-1">
                    <a href="#settingsSubmenu"
                       class="nav-link {{ request()->routeIs('admin.roles.*') || request()->routeIs('admin.audit-logs') ? 'active' : '' }}"
                       data-bs-toggle="collapse">
                        <i class="bi bi-gear-fill"></i>
                        <span>Settings</span>
                    </a>
                    <ul class="collapse sub-menu {{ request()->routeIs('admin.roles.*') || request()->routeIs('admin.audit-logs') ? 'show' : '' }}" id="settingsSubmenu">
                        @can('manage_roles')
                        <li>
                            <a href="{{ route('admin.roles.index') }}"
                               class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                <i class="bi bi-shield-lock-fill"></i>
                                <span>Roles & Permissions</span>
                            </a>
                        </li>
                        @endcan
                        @can('view_audit_logs')
                        <li>
                            <a href="{{ route('admin.audit-logs') }}"
                               class="nav-link {{ request()->routeIs('admin.audit-logs') ? 'active' : '' }}">
                                <i class="bi bi-journal-text"></i>
                                <span>Audit Logs</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
            </ul>
        </nav>
    </div>

    {{-- Sidebar Footer --}}
    <div class="px-3 pb-3 mt-auto">
        <div class="text-center">
            <small class="text-white-50">© {{ date('Y') }} RSCB v1.0</small>
        </div>
    </div>
</aside>
