<aside class="sidebar">
    <div class="sidebar-header">
        <a href="{{ url('/') }}" class="brand-logo">
            <img src="{{ asset('images/logo-white.png') }}" alt="RSCB" height="40">
            <span class="brand-text">RSCB</span>
        </a>
    </div>

    <div class="sidebar-menu">
        <nav class="menu">
            <ul class="nav flex-column">
                @role('super_admin')
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @endrole

                @role('regional_sports_secretary')
                <li class="nav-item">
                    <a href="{{ route('regional.dashboard') }}" class="nav-link {{ request()->routeIs('regional.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i>
                        <span>Regional Dashboard</span>
                    </a>
                </li>
                @endrole

                @role('airport_sports_secretary')
                <li class="nav-item">
                    <a href="{{ route('airport.dashboard') }}" class="nav-link {{ request()->routeIs('airport.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-building"></i>
                        <span>Airport Dashboard</span>
                    </a>
                </li>
                @endrole

                {{-- Region Management --}}
                @can('view_regions')
                <li class="nav-item">
                    <a href="{{ route('admin.regions.index') }}" class="nav-link {{ request()->routeIs('admin.regions.*') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt"></i>
                        <span>Regions</span>
                    </a>
                </li>
                @endcan

                {{-- Airport Management --}}
                @can('view_airports')
                <li class="nav-item">
                    <a href="{{ route('admin.airports.index') }}" class="nav-link {{ request()->routeIs('admin.airports.*') ? 'active' : '' }}">
                        <i class="bi bi-airplane"></i>
                        <span>Airports</span>
                    </a>
                </li>
                @endcan

                {{-- Employee Management --}}
                @can('view_employees')
                <li class="nav-item">
                    <a href="{{ route('admin.employees.index') }}" class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Employees</span>
                    </a>
                </li>
                @endcan

                {{-- Event Management --}}
                @can('view_events')
                <li class="nav-item has-submenu">
                    <a href="#eventSubmenu" class="nav-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}" data-bs-toggle="collapse">
                        <i class="bi bi-calendar-event"></i>
                        <span>Events</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul class="collapse {{ request()->routeIs('admin.events.*') ? 'show' : '' }}" id="eventSubmenu">
                        <li>
                            <a href="{{ route('admin.events.index') }}" class="nav-link">
                                <i class="bi bi-list-ul"></i>
                                <span>All Events</span>
                            </a>
                        </li>
                        @can('create_events')
                        <li>
                            <a href="{{ route('admin.events.create') }}" class="nav-link">
                                <i class="bi bi-plus-circle"></i>
                                <span>Create Event</span>
                            </a>
                        </li>
                        @endcan
                        @can('manage_form_templates')
                        <li>
                            <a href="{{ route('admin.forms.templates') }}" class="nav-link">
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
                <li class="nav-item">
                    <a href="{{ route('admin.registrations.index') }}" class="nav-link {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Registrations</span>
                        @if($pendingApprovals = \App\Models\EventRegistration::where('status', 'pending')->count())
                            <span class="badge bg-warning ms-2">{{ $pendingApprovals }}</span>
                        @endif
                    </a>
                </li>
                @endcan

                {{-- Reports --}}
                @can('view_reports')
                <li class="nav-item">
                    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span>Reports</span>
                    </a>
                </li>
                @endcan

                {{-- Certificates --}}
                @can('generate_certificates')
                <li class="nav-item">
                    <a href="{{ route('admin.certificates.index') }}" class="nav-link {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                        <i class="bi bi-award"></i>
                        <span>Certificates</span>
                    </a>
                </li>
                @endcan

                {{-- Announcements --}}
                @can('manage_announcements')
                <li class="nav-item">
                    <a href="{{ route('admin.announcements.index') }}" class="nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                        <i class="bi bi-megaphone"></i>
                        <span>Announcements</span>
                    </a>
                </li>
                @endcan

                {{-- Gallery --}}
                @can('manage_gallery')
                <li class="nav-item">
                    <a href="{{ route('admin.gallery.index') }}" class="nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                        <i class="bi bi-images"></i>
                        <span>Gallery</span>
                    </a>
                </li>
                @endcan

                {{-- Settings --}}
                @can('manage_settings')
                <li class="nav-item has-submenu">
                    <a href="#settingsSubmenu" class="nav-link" data-bs-toggle="collapse">
                        <i class="bi bi-gear"></i>
                        <span>Settings</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul class="collapse" id="settingsSubmenu">
                        @can('manage_roles')
                        <li>
                            <a href="{{ route('admin.roles.index') }}" class="nav-link">
                                <i class="bi bi-shield-lock"></i>
                                <span>Roles & Permissions</span>
                            </a>
                        </li>
                        @endcan
                        @can('view_audit_logs')
                        <li>
                            <a href="{{ route('admin.audit-logs') }}" class="nav-link">
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
</aside>
