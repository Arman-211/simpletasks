<?php
/**
 * @OA\Info(
 *     title="SimpleTasks API",
 *     version="0.1",
 *     description="This is the API documentation for the SimpleTasks application."
 * )
 */
return [
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'SimpleTasks API',
                'version' => '0.1',
            ],
            'routes' => [
                'api' => 'api/documentation',
            ],
            'paths' => [
                'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),
                'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSETS_PATH', 'vendor/swagger-api/swagger-ui/dist/'),
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),
                'annotations' => [
                    base_path('app'),
                ],
            ],
        ],
    ],
];

