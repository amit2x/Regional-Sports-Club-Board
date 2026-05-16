@extends('layouts.admin')

@section('title', 'Form Builder')

@push('styles')
<style>
    .form-builder-container {
        display: flex;
        gap: 1.5rem;
    }

    .field-palette {
        width: 280px;
        flex-shrink: 0;
    }

    .field-canvas {
        flex: 1;
        min-height: 600px;
    }

    .field-item {
        padding: 12px;
        margin-bottom: 8px;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        cursor: move;
        transition: all 0.2s;
    }

    .field-item:hover {
        border-color: #667eea;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
    }

    .field-item.dragging {
        opacity: 0.5;
        transform: rotate(2deg);
    }

    .canvas-field {
        background: white;
        border: 2px dashed #dee2e6;
        padding: 20px;
        margin-bottom: 12px;
        border-radius: 8px;
        position: relative;
    }

    .canvas-field:hover {
        border-color: #667eea;
    }

    .canvas-field .field-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        display: none;
    }

    .canvas-field:hover .field-actions {
        display: flex;
        gap: 5px;
    }

    .field-properties {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        margin-top: 10px;
    }

    .sortable-ghost {
        opacity: 0.4;
        background: #f0f0f0;
    }

    .sortable-chosen {
        background: #e3f2fd;
    }

    .form-preview {
        max-width: 800px;
        margin: 0 auto;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">
                <i class="bi bi-file-earmark-text me-2"></i>Dynamic Form Builder
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
                    <li class="breadcrumb-item active">Form Builder</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <button class="btn btn-outline-info" onclick="previewForm()">
                <i class="bi bi-eye me-1"></i>Preview
            </button>
            <button class="btn btn-success" onclick="saveForm()">
                <i class="bi bi-check-circle me-1"></i>Save Form
            </button>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Form Details --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Form Name *</label>
                            <input type="text" id="formName" class="form-control"
                                   value="{{ $template->name ?? '' }}"
                                   placeholder="Enter form name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="formDescription" class="form-control" rows="2"
                                      placeholder="Form description">{{ $template->description ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="isReusable"
                           {{ isset($template) && $template->is_reusable ? 'checked' : '' }}>
                    <label class="form-check-label" for="isReusable">
                        Make this template reusable for other events
                    </label>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Builder Area --}}
    <div class="col-12">
        <div class="form-builder-container">
            {{-- Field Palette --}}
            <div class="field-palette">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">Field Types</h6>
                    </div>
                    <div class="card-body p-2">
                        <div class="field-item" data-type="text" draggable="true">
                            <i class="bi bi-type me-2"></i>
                            <strong>Text Input</strong>
                            <small class="d-block text-muted">Single line text</small>
                        </div>

                        <div class="field-item" data-type="textarea" draggable="true">
                            <i class="bi bi-text-paragraph me-2"></i>
                            <strong>Textarea</strong>
                            <small class="d-block text-muted">Multi-line text</small>
                        </div>

                        <div class="field-item" data-type="number" draggable="true">
                            <i class="bi bi-123 me-2"></i>
                            <strong>Number</strong>
                            <small class="d-block text-muted">Numeric input</small>
                        </div>

                        <div class="field-item" data-type="email" draggable="true">
                            <i class="bi bi-envelope me-2"></i>
                            <strong>Email</strong>
                            <small class="d-block text-muted">Email address</small>
                        </div>

                        <div class="field-item" data-type="mobile" draggable="true">
                            <i class="bi bi-phone me-2"></i>
                            <strong>Mobile</strong>
                            <small class="d-block text-muted">Phone number</small>
                        </div>

                        <div class="field-item" data-type="dropdown" draggable="true">
                            <i class="bi bi-chevron-down me-2"></i>
                            <strong>Dropdown</strong>
                            <small class="d-block text-muted">Select options</small>
                        </div>

                        <div class="field-item" data-type="radio" draggable="true">
                            <i class="bi bi-ui-radios me-2"></i>
                            <strong>Radio Button</strong>
                            <small class="d-block text-muted">Single choice</small>
                        </div>

                        <div class="field-item" data-type="checkbox" draggable="true">
                            <i class="bi bi-check-square me-2"></i>
                            <strong>Checkbox</strong>
                            <small class="d-block text-muted">Multiple choices</small>
                        </div>

                        <div class="field-item" data-type="date" draggable="true">
                            <i class="bi bi-calendar-date me-2"></i>
                            <strong>Date Picker</strong>
                            <small class="d-block text-muted">Date selection</small>
                        </div>

                        <div class="field-item" data-type="file" draggable="true">
                            <i class="bi bi-cloud-upload me-2"></i>
                            <strong>File Upload</strong>
                            <small class="d-block text-muted">Document upload</small>
                        </div>

                        <div class="field-item" data-type="declaration" draggable="true">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Declaration</strong>
                            <small class="d-block text-muted">Agreement checkbox</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Canvas Area --}}
            <div class="field-canvas">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check me-2"></i>Form Fields
                        </h5>
                        <small class="text-muted">Drag and drop fields here</small>
                    </div>
                    <div class="card-body" id="formCanvas">
                        @if(isset($template) && $template->form_schema)
                            @foreach($template->form_schema['fields'] as $index => $field)
                                @include('admin.forms.partials.field-item', ['field' => $field, 'index' => $index])
                            @endforeach
                        @else
                            <div class="empty-canvas text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 48px;"></i>
                                <p class="text-muted mt-3">Drag fields from the palette to build your form</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Preview Modal --}}
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="formPreviewContainer"></div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden template for new fields --}}
<template id="fieldTemplate">
    <div class="canvas-field" data-field-index="{index}">
        <div class="field-actions">
            <button class="btn btn-sm btn-light" onclick="editField(this)" title="Edit">
                <i class="bi bi-gear"></i>
            </button>
            <button class="btn btn-sm btn-light" onclick="duplicateField(this)" title="Duplicate">
                <i class="bi bi-copy"></i>
            </button>
            <button class="btn btn-sm btn-danger" onclick="removeField(this)" title="Delete">
                <i class="bi bi-trash"></i>
            </button>
        </div>

        <div class="field-preview">
            <label class="fw-bold mb-2">{label} {required}</label>
            <div class="field-render"></div>
        </div>

        <div class="field-properties mt-3" style="display:none;">
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label small">Field Label</label>
                    <input type="text" class="form-control form-control-sm field-label" value="{label}" onchange="updateFieldPreview(this)">
                </div>
                <div class="col-md-6">
                    <label class="form-label small">Field Name (unique)</label>
                    <input type="text" class="form-control form-control-sm field-name" value="{name}" onchange="updateFieldName(this)">
                </div>
                <div class="col-md-12">
                    <label class="form-label small">Placeholder / Help Text</label>
                    <input type="text" class="form-control form-control-sm field-placeholder" value="{placeholder}">
                </div>
                <div class="col-md-4">
                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input field-required" {required_checked} onchange="updateFieldPreview(this)">
                        <label class="form-check-label">Required</label>
                    </div>
                </div>
                <div class="col-md-8 options-container" style="display:none;">
                    <label class="form-label small">Options (one per line)</label>
                    <textarea class="form-control form-control-sm field-options" rows="3">{options}</textarea>
                </div>
            </div>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let fieldCounter = 0;

$(document).ready(function() {
    // Initialize existing fields count
    fieldCounter = $('#formCanvas .canvas-field').length;

    // Initialize Sortable
    new Sortable(document.getElementById('formCanvas'), {
        animation: 150,
        handle: '.canvas-field',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: function() {
            reindexFields();
        }
    });

    // Drag from palette
    $('.field-item').on('dragstart', function(e) {
        e.originalEvent.dataTransfer.setData('fieldType', $(this).data('type'));
    });

    // Drop on canvas
    $('#formCanvas').on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('bg-light');
    });

    $('#formCanvas').on('dragleave', function(e) {
        $(this).removeClass('bg-light');
    });

    $('#formCanvas').on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('bg-light');

        let fieldType = e.originalEvent.dataTransfer.getData('fieldType');
        if (fieldType) {
            addField(fieldType);
        }

        $('.empty-canvas').remove();
    });
});

function addField(type, fieldData = null) {
    let label = fieldData?.label || getDefaultLabel(type);
    let name = fieldData?.name || 'field_' + fieldCounter;
    let placeholder = fieldData?.placeholder || '';
    let required = fieldData?.required || false;
    let options = fieldData?.options || [];
    let optionText = Array.isArray(options) ? options.join('\n') : '';

    let fieldHtml = $('#fieldTemplate').html()
        .replace(/{index}/g, fieldCounter)
        .replace(/{label}/g, label)
        .replace(/{name}/g, name)
        .replace(/{placeholder}/g, placeholder)
        .replace(/{required}/g, required ? '<span class="text-danger">*</span>' : '')
        .replace(/{required_checked}/g, required ? 'checked' : '')
        .replace(/{options}/g, optionText);

    let $field = $(fieldHtml);

    // Render field preview based on type
    renderFieldPreview($field, type, placeholder, options);

    // Store field type
    $field.attr('data-field-type', type);

    $('#formCanvas').append($field);

    // Show options for dropdown/radio/checkbox
    if (['dropdown', 'radio', 'checkbox'].includes(type)) {
        $field.find('.options-container').show();
    }

    fieldCounter++;
}

function renderFieldPreview($field, type, placeholder, options) {
    let renderHtml = '';

    switch(type) {
        case 'text':
            renderHtml = `<input type="text" class="form-control" placeholder="${placeholder}" disabled>`;
            break;
        case 'textarea':
            renderHtml = `<textarea class="form-control" rows="3" placeholder="${placeholder}" disabled></textarea>`;
            break;
        case 'number':
            renderHtml = `<input type="number" class="form-control" placeholder="${placeholder}" disabled>`;
            break;
        case 'email':
            renderHtml = `<input type="email" class="form-control" placeholder="${placeholder}" disabled>`;
            break;
        case 'mobile':
            renderHtml = `<input type="tel" class="form-control" placeholder="${placeholder}" disabled>`;
            break;
        case 'date':
            renderHtml = `<input type="date" class="form-control" disabled>`;
            break;
        case 'file':
            renderHtml = `<input type="file" class="form-control" disabled>
                         <small class="text-muted">${placeholder || 'Accepted formats: PDF, JPG, PNG (Max 5MB)'}</small>`;
            break;
        case 'dropdown':
            renderHtml = `<select class="form-select" disabled>
                            <option>${placeholder || 'Select an option'}</option>
                            ${options.map(opt => `<option>${opt}</option>`).join('')}
                         </select>`;
            break;
        case 'radio':
            renderHtml = options.map((opt, i) =>
                `<div class="form-check">
                    <input type="radio" class="form-check-input" name="radio_${fieldCounter}" disabled>
                    <label class="form-check-label">${opt}</label>
                </div>`
            ).join('');
            break;
        case 'checkbox':
            renderHtml = options.map((opt, i) =>
                `<div class="form-check">
                    <input type="checkbox" class="form-check-input" disabled>
                    <label class="form-check-label">${opt}</label>
                </div>`
            ).join('');
            break;
        case 'declaration':
            renderHtml = `<div class="form-check">
                            <input type="checkbox" class="form-check-input" disabled>
                            <label class="form-check-label">${placeholder || 'I agree to the terms and conditions'}</label>
                         </div>`;
            break;
    }

    $field.find('.field-render').html(renderHtml);
}

function editField(btn) {
    let $field = $(btn).closest('.canvas-field');
    $field.find('.field-properties').slideToggle();
}

function removeField(btn) {
    Swal.fire({
        title: 'Remove Field?',
        text: 'Are you sure you want to remove this field?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Remove'
    }).then((result) => {
        if (result.isConfirmed) {
            $(btn).closest('.canvas-field').slideUp(300, function() {
                $(this).remove();
                if ($('#formCanvas .canvas-field').length === 0) {
                    $('#formCanvas').html(`
                        <div class="empty-canvas text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-3">Drag fields from the palette to build your form</p>
                        </div>
                    `);
                }
            });
        }
    });
}

function duplicateField(btn) {
    let $field = $(btn).closest('.canvas-field');
    let $clone = $field.clone();
    let newIndex = fieldCounter++;

    $clone.attr('data-field-index', newIndex);
    $clone.find('.field-name').val('field_' + newIndex);
    $clone.find('.field-properties').hide();

    $('#formCanvas').append($clone);
}

function updateFieldPreview(input) {
    let $field = $(input).closest('.canvas-field');
    let label = $field.find('.field-label').val();
    let required = $field.find('.field-required').prop('checked');

    $field.find('.field-preview label').html(
        label + (required ? ' <span class="text-danger">*</span>' : '')
    );
}

function updateFieldName(input) {
    let name = $(input).val();
    // Convert to valid field name
    name = name.toLowerCase().replace(/[^a-z0-9_]/g, '_');
    $(input).val(name);
}

function reindexFields() {
    $('#formCanvas .canvas-field').each(function(i) {
        $(this).attr('data-field-index', i);
    });
}

function getDefaultLabel(type) {
    let labels = {
        text: 'Text Field',
        textarea: 'Text Area',
        number: 'Number Field',
        email: 'Email Address',
        mobile: 'Mobile Number',
        dropdown: 'Select Option',
        radio: 'Choose One',
        checkbox: 'Select Options',
        date: 'Date',
        file: 'Upload File',
        declaration: 'Declaration'
    };
    return labels[type] || 'New Field';
}

function saveForm() {
    let formName = $('#formName').val();
    if (!formName) {
        Swal.fire('Error', 'Please enter form name', 'error');
        return;
    }

    let fields = [];
    $('#formCanvas .canvas-field').each(function() {
        let $field = $(this);
        let fieldData = {
            type: $field.attr('data-field-type'),
            label: $field.find('.field-label').val(),
            name: $field.find('.field-name').val(),
            placeholder: $field.find('.field-placeholder').val(),
            required: $field.find('.field-required').prop('checked'),
        };

        // Get options for choice fields
        if (['dropdown', 'radio', 'checkbox'].includes(fieldData.type)) {
            let optionsText = $field.find('.field-options').val();
            fieldData.options = optionsText.split('\n').filter(opt => opt.trim());
        }

        fields.push(fieldData);
    });

    if (fields.length === 0) {
        Swal.fire('Error', 'Please add at least one field', 'error');
        return;
    }

    let formData = {
        name: formName,
        description: $('#formDescription').val(),
        is_reusable: $('#isReusable').prop('checked'),
        form_schema: JSON.stringify({
            fields: fields,
            version: '1.0'
        }),
        _token: '{{ csrf_token() }}'
    };

    @if(isset($template))
        formData.template_id = {{ $template->id }};
    @endif

    $.ajax({
        url: '{{ route("admin.forms.save-template") }}',
        type: 'POST',
        data: formData,
        success: function(response) {
            Swal.fire({
                title: 'Success!',
                text: response.message,
                icon: 'success'
            }).then(() => {
                if (response.template_id) {
                    window.location.href = '{{ route("admin.events.index") }}';
                }
            });
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Failed to save form', 'error');
        }
    });
}

function previewForm() {
    let fields = [];
    $('#formCanvas .canvas-field').each(function() {
        let $field = $(this);
        fields.push({
            type: $field.attr('data-field-type'),
            label: $field.find('.field-label').val(),
            name: $field.find('.field-name').val(),
            placeholder: $field.find('.field-placeholder').val(),
            required: $field.find('.field-required').prop('checked'),
            options: $field.find('.field-options').val().split('\n').filter(opt => opt.trim())
        });
    });

    if (fields.length === 0) {
        Swal.fire('Preview', 'No fields to preview', 'info');
        return;
    }

    let previewHtml = '<form><div class="row g-3">';

    fields.forEach(field => {
        previewHtml += '<div class="col-md-12 mb-3">';
        previewHtml += `<label class="form-label">${field.label} ${field.required ? '<span class="text-danger">*</span>' : ''}</label>`;

        switch(field.type) {
            case 'text':
                previewHtml += `<input type="text" class="form-control" placeholder="${field.placeholder}">`;
                break;
            case 'textarea':
                previewHtml += `<textarea class="form-control" rows="3" placeholder="${field.placeholder}"></textarea>`;
                break;
            case 'number':
                previewHtml += `<input type="number" class="form-control" placeholder="${field.placeholder}">`;
                break;
            case 'email':
                previewHtml += `<input type="email" class="form-control" placeholder="${field.placeholder}">`;
                break;
            case 'mobile':
                previewHtml += `<input type="tel" class="form-control" placeholder="${field.placeholder}">`;
                break;
            case 'date':
                previewHtml += `<input type="date" class="form-control">`;
                break;
            case 'file':
                previewHtml += `<input type="file" class="form-control">
                               <small class="text-muted">${field.placeholder}</small>`;
                break;
            case 'dropdown':
                previewHtml += `<select class="form-select">
                                    <option>${field.placeholder || 'Select'}</option>
                                    ${field.options.map(opt => `<option>${opt}</option>`).join('')}
                                </select>`;
                break;
            case 'radio':
                field.options.forEach(opt => {
                    previewHtml += `<div class="form-check">
                                        <input type="radio" class="form-check-input">
                                        <label class="form-check-label">${opt}</label>
                                    </div>`;
                });
                break;
            case 'checkbox':
                field.options.forEach(opt => {
                    previewHtml += `<div class="form-check">
                                        <input type="checkbox" class="form-check-input">
                                        <label class="form-check-label">${opt}</label>
                                    </div>`;
                });
                break;
            case 'declaration':
                previewHtml += `<div class="form-check">
                                    <input type="checkbox" class="form-check-input">
                                    <label class="form-check-label">${field.placeholder}</label>
                                </div>`;
                break;
        }
        previewHtml += '</div>';
    });

    previewHtml += '</div></form>';

    $('#formPreviewContainer').html(previewHtml);
    $('#previewModal').modal('show');
}
</script>
@endpush
