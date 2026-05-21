@php
    $selectedPermissions = $selectedPermissions ?? [];
    $columns = [
        'read' => 'Read',
        'view' => 'View',
        'create' => 'Create',
        'update' => 'Update',
        'delete' => 'Delete',
        'publish' => 'Publish',
    ];
    $options = [
        'all' => 'All',
        'none' => 'None',
        'only' => 'Only',
    ];
    $renderRows = function ($items, $depth = 0) use (&$renderRows, $columns, $options, $selectedPermissions) {
        foreach ($items as $item) {
            $access = $selectedPermissions[$item->id] ?? ($item->access ?? []);
            $indent = $depth * 18;
            echo '<tr>';
            echo '<td class="text-start" style="padding-left:' . (16 + $indent) . 'px">';
            echo '<div class="fw-semibold">' . e($item->name) . '</div>';
            echo '<div class="text-muted small">ID Menu: ' . e($item->id) . '</div>';
            echo '</td>';
            foreach ($columns as $key => $label) {
                echo '<td class="text-center">';
                echo '<select class="form-select form-select-sm mx-auto" style="min-width: 92px;" name="accesses[' .
                    e($item->id) .
                    '][' .
                    e($key) .
                    ']">';
                foreach ($options as $value => $text) {
                    $selected = ($access[$key] ?? 'none') === $value ? ' selected' : '';
                    echo '<option value="' . e($value) . '"' . $selected . '>' . e($text) . '</option>';
                }
                echo '</select>';
                echo '</td>';
            }
            echo '</tr>';

            if (!empty($item->tree)) {
                $renderRows($item->tree, $depth + 1);
            }
        }
    };
@endphp

<div class="table-responsive">
    <table class="table-bordered mb-0 table align-middle">
        <thead class="table-light">
            <tr>
                <th style="min-width: 280px;">Menu</th>
                @foreach ($columns as $label)
                    <th class="text-center" style="width: 120px;">
                        <div>{{ $label }}</div>
                        <div class="small text-muted">All / None / Only</div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if (!empty($permissions))
                @php $renderRows($permissions); @endphp
            @else
                <tr>
                    <td colspan="7" class="text-muted py-4 text-center">Tidak ada menu yang bisa diatur.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
