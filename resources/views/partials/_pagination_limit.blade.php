<div class="d-flex align-items-center gap-2">
    <label for="per_page" class="text-muted small mb-0">عدد السجلات:</label>
    <select name="per_page" id="per_page" class="form-select form-select-sm rounded-pill" style="width: auto;" onchange="this.form.submit()">
        @foreach([50, 500, 1000] as $limit)
            <option value="{{ $limit }}" {{ request('per_page', 50) == $limit ? 'selected' : '' }}>{{ $limit }}</option>
        @endforeach
    </select>
</div>
