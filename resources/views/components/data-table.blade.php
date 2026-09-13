<!-- resources/views/components/data-table.blade.php -->
{{-- Data table component matching compact global SaaS design systems --}}
@props([
    'columns' => [],
    'rows' => [],
])
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm overflow-hidden transition-colors print:shadow-none print:border-none print:bg-transparent">
    <div class="overflow-x-auto">
        <table class="w-full text-right border-collapse whitespace-nowrap">
            <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400">
                <tr>
                    @foreach($columns as $col)
                        <th class="px-3 py-1.5 font-semibold text-[11px] uppercase tracking-wider">
                            {{ $col['label'] ?? $col }}
                        </th>
                    @endforeach
                    @if(isset($actions))
                        <th class="px-3 py-1.5 font-semibold text-[11px] uppercase tracking-wider text-center w-1">
                            الإجراءات
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($rows as $row)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/25 transition-colors group">
                        @foreach($columns as $col)
                            @php $field = $col['field'] ?? $col; @endphp
                            <td class="px-3 py-1.5 text-slate-600 dark:text-slate-300 text-xs">
                                {{ $row->$field ?? '' }}
                            </td>
                        @endforeach
                        @if(isset($actions))
                            <td class="px-3 py-1.5 text-center">
                                <div class="flex justify-center items-center gap-1.5">
                                    {{ $actions }}
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + (isset($actions) ? 1 : 0) }}" class="px-3 py-6 text-center text-slate-400 dark:text-slate-500 text-xs">
                            <div class="flex flex-col items-center justify-center gap-1.5">
                                <i class="fas fa-inbox text-slate-300 dark:text-slate-600 text-lg"></i>
                                <span>لا توجد سجلات متاحة للعرض</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>