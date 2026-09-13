{{-- resources/views/components/image-upload.blade.php --}}
{{-- Smart Image Upload Component matching tem FormTemplate.tsx --}}
@props([
    'name' => 'photo',
    'label' => 'الصورة الشخصية',
    'currentImage' => null,
    'required' => false,
    'id' => 'image_upload_' . uniqid(),
])

<div class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30 rounded-2xl p-6 min-h-[350px] h-full transition-colors w-full" id="{{ $id }}_wrapper">
    <div class="relative w-48 h-48 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex items-center justify-center overflow-hidden mb-6 shadow-sm group transition-all">
        <img id="{{ $id }}_preview" 
             src="{{ $currentImage ? asset('storage/' . $currentImage) : '' }}" 
             alt="Preview" 
             class="w-full h-full object-cover {{ $currentImage ? '' : 'hidden' }}" />
        
        <div id="{{ $id }}_placeholder" class="flex flex-col items-center text-slate-400 dark:text-slate-500 gap-2 {{ $currentImage ? 'hidden' : '' }}">
            <svg class="w-8 h-8 stroke-[1.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                <circle cx="9" cy="9" r="2"/>
                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
            </svg>
            <span class="text-sm font-semibold">{{ $label }} @if($required) <span class="text-red-500">*</span> @endif</span>
        </div>

        <button 
            type="button"
            id="{{ $id }}_clear"
            class="absolute top-2 right-2 p-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity shadow-md cursor-pointer {{ $currentImage ? '' : 'hidden' }}"
            title="حذف الصورة"
        >
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>
    
    <button 
        type="button"
        id="{{ $id }}_btn"
        class="flex items-center gap-2 px-6 py-2.5 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 font-bold hover:bg-brand-100 dark:hover:bg-brand-500/20 transition-colors text-sm cursor-pointer"
    >
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="M12 12v9"/><path d="m16 16-4-4-4 4"/></svg>
        <span id="{{ $id }}_btn_text">{{ $currentImage ? 'تغيير الصورة' : 'رفع صورة' }}</span>
    </button>
    
    <input 
        type="file" 
        name="{{ $name }}"
        id="{{ $id }}_input"
        accept="image/*" 
        class="hidden"
        {{ $required && !$currentImage ? 'required' : '' }}
    />
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('{{ $id }}_input');
    const preview = document.getElementById('{{ $id }}_preview');
    const placeholder = document.getElementById('{{ $id }}_placeholder');
    const clearBtn = document.getElementById('{{ $id }}_clear');
    const uploadBtn = document.getElementById('{{ $id }}_btn');
    const btnText = document.getElementById('{{ $id }}_btn_text');

    uploadBtn?.addEventListener('click', () => input?.click());

    input?.addEventListener('change', function(e) {
        const file = e.target.files?.[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                clearBtn.classList.remove('hidden');
                btnText.textContent = 'تغيير الصورة';
            };
            reader.readAsDataURL(file);
        }
    });

    clearBtn?.addEventListener('click', function(e) {
        e.preventDefault();
        if (input) input.value = '';
        if (preview) {
            preview.src = '';
            preview.classList.add('hidden');
        }
        if (placeholder) placeholder.classList.remove('hidden');
        clearBtn.classList.add('hidden');
        btnText.textContent = 'رفع صورة';
    });
});
</script>
