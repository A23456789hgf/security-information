<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1c2e;
        margin: 0;
    }
    
    .nav-tabs-custom {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .nav-tab {
        padding: 10px 20px;
        border-radius: 8px;
        background: #f0f0f0;
        color: #666;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .nav-tab.active, .nav-tab:hover {
        background: #1a1c2e;
        color: #fff;
    }

    .diff-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 25px;
    }

    @media (max-width: 1023px) {
        .diff-layout {
            grid-template-columns: 1fr;
        }
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
    }

    .main-panel {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 25px;
    }

    .sidebar-panel {
        background: linear-gradient(180deg, #1a1c2e 0%, #252841 100%);
        border-radius: 12px;
        padding: 20px;
        color: #fff;
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }

    .sidebar-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .result-item {
        background: rgba(255,255,255,0.05);
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .result-item:hover {
        background: rgba(76, 201, 240, 0.15);
        transform: translateX(-5px);
    }

    .result-item.active {
        background: rgba(76, 201, 240, 0.25);
        border-right: 3px solid #4cc9f0;
    }

    .result-label {
        font-size: 0.9rem;
    }

    .result-count {
        background: #4cc9f0;
        color: #1a1c2e;
        padding: 3px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .result-count.zero {
        background: rgba(255,255,255,0.2);
        color: #999;
    }

    .upload-btnsecondary {
        border: 2px dashed rgba(255,255,255,0.3);
        border-radius: 12px;
        padding: 30px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 20px;
    }

    .upload-btnsecondary:hover {
        background: rgba(255,255,255,0.05);
        border-color: #4cc9f0;
    }

    .upload-btnsecondary i {
        font-size: 2rem;
        color: #4cc9f0;
        margin-bottom: 10px;
    }

    .primary-info {
        background: rgba(76, 201, 240, 0.1);
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .primary-info .info-label {
        font-size: 0.8rem;
        color: #888;
    }

    .primary-info .info-value {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a1c2e;
    }

    .data-preview {
        max-height: 400px;
        overflow-y: auto;
    }

    .data-preview table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-preview th {
        background: #1a1c2e;
        color: #fff;
        padding: 10px 12px;
        text-align: right;
        font-size: 0.85rem;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .data-preview td {
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
        font-size: 0.85rem;
    }

    .data-preview tr:hover td {
        background: #f8f9ff;
    }

    .match-highlight {
        background: #d4edda !important;
    }

    .diff-highlight {
        background: #f8d7da !important;
    }

    .empty-results {
        text-align: center;
        padding: 40px 20px;
        color: rgba(255,255,255,0.5);
    }

    .empty-results i {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .category-header {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: rgba(255,255,255,0.5);
        padding: 10px 0 5px;
        margin-top: 15px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        flex-direction: column;
        gap: 20px;
    }

    .loading-overlay.active {
        display: flex;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid rgba(255,255,255,0.2);
        border-top-color: #4cc9f0;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .detail-panel {
        display: none;
    }

    .detail-panel.active {
        display: block;
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .back-btn {
        background: none;
        border: none;
        color: #4cc9f0;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .tabs-container {
        display: flex;
        gap: 15px;
        border-bottom: 1px solid #eee;
        margin-bottom: 20px;
    }

    .tab-btn {
        padding: 10px 5px;
        background: none;
        border: none;
        color: #666;
        font-weight: 600;
        cursor: pointer;
        position: relative;
        transition: all 0.3s;
    }

    .tab-btn.active {
        color: #1a1c2e;
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: #1a1c2e;
    }

    .action-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 6px 15px;
        border-radius: 6px;
        font-size: 0.85rem;
        border: 1px solid #ddd;
        background: #fff;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-action:hover {
        background: #f8f9fa;
        border-color: #ccc;
    }

    .btn-action.primary {
        background: #1a1c2e;
        color: #fff;
        border-color: #1a1c2e;
    }

    .btn-action.primary:hover {
        background: #252841;
    }

    /* Modal Overlay Custom */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 2100;
        backdrop-filter: blur(4px);
    }
    
    .modal-overlay.active {
        display: flex;
    }
    
    .modal-box {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        color: #1a1c2e;
        direction: rtl;
    }

    /* Progress Checklist Steps */
    .progress-steps {
        background: #f8f9fc;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #eef2f5;
    }
    
    .progress-steps .step-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        color: #888;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    
    .progress-steps .step-item:last-child {
        margin-bottom: 0;
    }
    
    .progress-steps .step-item.active {
        color: #1a1c2e;
        font-weight: 600;
        opacity: 1 !important;
    }
    
    .progress-steps .step-item.completed {
        color: #2ec4b6;
        opacity: 0.9 !important;
    }
    
    .progress-steps .step-item i {
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }
    
    .progress-steps .step-item.completed i {
        color: #2ec4b6;
    }

    /* Action Buttons in table rows */
    .row-btn-action {
        background: none;
        border: none;
        color: #1a1c2e;
        cursor: pointer;
        padding: 2px 6px;
        font-size: 0.8rem;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: background 0.2s;
    }

    .row-btn-action:hover {
        background: #f0f1f5;
        color: #4cc9f0;
    }
</style>


