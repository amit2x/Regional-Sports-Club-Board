<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FormBuilderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employee');
        $this->middleware('permission:manage_form_templates');
    }

    /**
     * Display form builder
     */
    public function builder($id = null)
    {
        $template = null;
        if ($id) {
            $template = FormTemplate::findOrFail($id);
        }

        $templates = FormTemplate::where('is_active', true)
            ->where('is_reusable', true)
            ->get();

        return view('admin.forms.builder', compact('template', 'templates'));
    }

    /**
     * Save form template
     */
    public function saveTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'form_schema' => 'required|json',
            'is_reusable' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $formSchema = json_decode($request->form_schema, true);

        if (empty($formSchema['fields'])) {
            return response()->json([
                'success' => false,
                'message' => 'Form must have at least one field.'
            ], 422);
        }

        // Generate validation rules from schema
        $validationRules = $this->generateValidationRules($formSchema['fields']);

        $templateData = [
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'description' => $request->description,
            'form_schema' => $formSchema,
            'validation_rules' => $validationRules,
            'is_active' => true,
            'is_reusable' => $request->is_reusable ?? false,
            'created_by' => auth()->guard('employee')->id(),
        ];

        if ($request->template_id) {
            $template = FormTemplate::findOrFail($request->template_id);
            $template->update($templateData);
            $message = 'Form template updated successfully.';
        } else {
            $template = FormTemplate::create($templateData);
            $message = 'Form template created successfully.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'template_id' => $template->id
        ]);
    }

    /**
     * Preview form
     */
    public function preview($id)
    {
        $template = FormTemplate::findOrFail($id);
        return view('admin.forms.preview', compact('template'));
    }

    /**
     * Generate validation rules from form schema
     */
    private function generateValidationRules($fields)
    {
        $rules = [];

        foreach ($fields as $field) {
            $fieldRules = [];

            if ($field['required'] ?? false) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            switch ($field['type']) {
                case 'text':
                case 'textarea':
                    $fieldRules[] = 'string';
                    if (isset($field['max_length'])) {
                        $fieldRules[] = 'max:' . $field['max_length'];
                    }
                    break;

                case 'number':
                    $fieldRules[] = 'numeric';
                    if (isset($field['min'])) {
                        $fieldRules[] = 'min:' . $field['min'];
                    }
                    if (isset($field['max'])) {
                        $fieldRules[] = 'max:' . $field['max'];
                    }
                    break;

                case 'email':
                    $fieldRules[] = 'email';
                    break;

                case 'mobile':
                    $fieldRules[] = 'regex:/^[0-9]{10}$/';
                    break;

                case 'file':
                    $fieldRules[] = 'file';
                    if (isset($field['max_size'])) {
                        $fieldRules[] = 'max:' . $field['max_size'];
                    }
                    if (isset($field['allowed_types'])) {
                        $fieldRules[] = 'mimes:' . implode(',', $field['allowed_types']);
                    }
                    break;

                case 'date':
                    $fieldRules[] = 'date';
                    break;

                case 'dropdown':
                case 'radio':
                    if (isset($field['options'])) {
                        $options = array_keys($field['options']);
                        $fieldRules[] = 'in:' . implode(',', $options);
                    }
                    break;

                case 'checkbox':
                    $fieldRules[] = 'array';
                    break;

                case 'declaration':
                    $fieldRules[] = 'accepted';
                    break;
            }

            $rules[$field['name']] = implode('|', $fieldRules);
        }

        return $rules;
    }

    /**
     * Get form templates list
     */
    public function templatesList()
    {
        $templates = FormTemplate::where('is_active', true)
            ->with('creator')
            ->latest()
            ->paginate(10);

        return view('admin.forms.templates', compact('templates'));
    }
}
