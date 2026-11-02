<style>
    .diff-container {
        font-family: 'Cairo', sans-serif;
    }
    .diff-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease-in-out;
    }
    .dark .diff-card {
        background: #0f172a;
        border-color: #1e293b;
    }
    .diff-tab-btn {
        padding: 0.75rem 1.25rem;
        font-weight: 700;
        font-size: 0.875rem;
        border-radius: 0.75rem;
        color: #64748b;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .diff-tab-btn:hover {
        color: #3b82f6;
        background: rgba(59, 130, 246, 0.05);
    }
    .diff-tab-btn.active {
        color: #ffffff;
        background: #2563eb;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }
    .stat-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .badge-matched { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .badge-different { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); }
    .badge-sec-only { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2); }
    .badge-pri-only { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; border: 1px solid rgba(139, 92, 246, 0.2); }
    .badge-incomplete { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    
    .mapping-row {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
    }
    .dark .mapping-row {
        background: #1e293b/40;
        border-color: #334155;
    }
    .diff-table th {
        background: #f1f5f9;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 0.75rem 1rem;
    }
    .dark .diff-table th {
        background: #1e293b;
        color: #94a3b8;
    }
    .diff-table td {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .dark .diff-table td {
        border-bottom-color: #1e293b;
    }
    .diff-highlight {
        background: #fef2f2;
        color: #991b1b;
        font-weight: 700;
        padding: 0.2rem 0.4rem;
        border-radius: 0.375rem;
    }
    .dark .diff-highlight {
        background: rgba(239, 68, 68, 0.2);
        color: #fca5a5;
    }
</style>
