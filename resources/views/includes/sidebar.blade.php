<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a
                href="{{ route('dashboard.index') }}"
                class="nav-link {{ Illuminate\Support\Facades\Route::current()->getName() === 'dashboard.index' ? 'active' : '' }}"
            >
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Дашборд
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a
                href="{{ route('products.index') }}"
                class="nav-link {{ Illuminate\Support\Facades\Route::current()->getName() === 'products.index' ? 'active' : '' }}"
            >
                <i class="nav-icon fas fa-th"></i>
                <p>
                    Продукты
                </p>
            </a>
        </li>
    </ul>
</nav>
