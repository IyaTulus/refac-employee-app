@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Schema;
    use jeemce\models\Notify;

    $hasNotifyTable = Schema::hasTable('notifies');
    $notifyCount = $hasNotifyTable ? Notify::_count(['status' => 'unread']) : 0;
    $notifyModels = $hasNotifyTable ? Notify::latestModels() : collect();
    $hasNotifyShow = Route::has('jeemce.notify.show');
    $hasNotifyIndex = Route::has('jeemce.notify.index');
@endphp


<button type="button"
    class="navbar-notify-toggle btn position-relative d-flex align-items-center justify-content-center rounded-circle me-3 bg-white"
    data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" aria-label="Notifications">

    <i class="bi bi-bell-fill fs-5 text-secondary"></i>

    @if ($notifyCount > 0)
        <span class="navbar-notify-badge badge bg-danger rounded-pill">
            {{ $notifyCount > 99 ? '99+' : $notifyCount }}
        </span>
    @endif
</button>
<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow navbar-notify-menu border-0 p-1 shadow-lg">
    <?php $unreadCount = 0; ?>
    @forelse ($notifyModels as $notifyModel)
        <?php $unreadCount++; ?>
        <li>
            @if ($hasNotifyShow)
                <a href="{{ route('jeemce.notify.show', ['notify' => $notifyModel->id]) }}" @class([
                    'dropdown-item navbar-notify-item',
                    'fw-bold' => $notifyModel->status === 'unread',
                ])>
                    {{ $notifyModel->content }}
                </a>
            @else
                <span @class([
                    'dropdown-item navbar-notify-item',
                    'fw-bold' => $notifyModel->status === 'unread',
                ])>
                    {{ $notifyModel->content }}
                </span>
            @endif
        </li>
    @empty
        <li><span class="dropdown-item navbar-notify-item text-muted">Data Tidak Tersedia</span></li>
    @endforelse

    @if ($unreadCount > 0 && $hasNotifyIndex)
        <li>
            <a href="{{ route('jeemce.notify.index') }}" class="dropdown-item navbar-notify-item">Clear</a>
        </li>
    @endif
</ul>
