<div class="cardNav">

    <nav>
        <ul>
            <li>
                <form action="{{ route('language.switch') }}" method="POST">
                    @csrf
                    <div class="select-wrapper">
                        <select name="language" onchange="this.form.submit()" id="language">
                            <option value="en" {{ App::getLocale() == 'en' ? 'selected' : '' }}>
                            🇬🇧 {{ trans('messages.english') }}
                            </option>
                            <option value="mm" {{ App::getLocale() == 'mm' ? 'selected' : '' }}>
                            🇲🇲 {{ trans('messages.myanmar') }}
                            </option>
                        </select>
                    </div>
                </form>
            </li>
            <li>
                <button type="button" class="btnText btn" data-bs-toggle="modal" data-bs-target="#confirmationLogoutModal">
                    {{ trans('messages.logout') }}
                </button>
            </li>
            <li>
                <form method="get" action="{{ route('employees.lists') }}">
                    <button type="submit" class="btnText btn">{{ trans('messages.lists') }}</button>
                </form>
            </li>
            <li>
                <form method="get" action="{{ route('employees.register') }}">
                    <button type="submit" class="btnText btn">{{ trans('messages.register') }}</button>
                </form>
            </li>
        </ul>

    </nav>
    <br>
    <h1>{{ trans('messages.employee_registration_system') }}</h1>
    <div class="iDClass">
        <button type="button" class="btn btn-light" disabled>
        {{ trans('messages.employee_id') }} : {{session('employee')->employee_id}}
        </button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmationLogoutModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Logout</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <p><b>{{ trans('messages.logout_confirm') }}</b></p>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('messages.cancel') }}</button>
                <form method="get" action="{{ route('logout') }}">
                    <button type="submit" class="btn btn-primary">{{ trans('messages.logout_btn') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>