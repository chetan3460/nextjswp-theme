<?php
/**
 * ACF Field Registration for Homepage Blocks
 * These fields are also saved in acf-json/group_homepage_content.json
 */

if (function_exists('acf_add_local_field_group')):

    acf_add_local_field_group(array(
        'key' => 'group_homepage_content',
        'title' => 'Homepage Content',
        'fields' => array(
            array(
                'key' => 'field_hp_flexible_content',
                'label' => 'Homepage Blocks',
                'name' => 'homepage_blocks',
                'type' => 'flexible_content',
                'instructions' => 'Add and arrange content blocks for the homepage.',
                'required' => 0,
                'button_label' => 'Add Block',
                'layouts' => array(
                    // HERO BLOCK
                    'layout_65f1a2b3c4d5f' => array(
                        'key' => 'layout_65f1a2b3c4d5f',
                        'name' => 'home_hero_block',
                        'label' => 'Hero Section',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_hero_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                            ),
                            array(
                                'key' => 'field_hero_subtitle',
                                'label' => 'Subtitle',
                                'name' => 'subtitle',
                                'type' => 'text',
                            ),
                            array(
                                'key' => 'field_hero_text',
                                'label' => 'Description',
                                'name' => 'text',
                                'type' => 'textarea',
                            ),
                            array(
                                'key' => 'field_hero_cta_text',
                                'label' => 'CTA Text',
                                'name' => 'cta_text',
                                'type' => 'text',
                            ),
                            array(
                                'key' => 'field_hero_cta_link',
                                'label' => 'CTA Link',
                                'name' => 'cta_link',
                                'type' => 'url',
                            ),
                            array(
                                'key' => 'field_hero_image',
                                'label' => 'Background Image',
                                'name' => 'image',
                                'type' => 'image',
                                'return_format' => 'array',
                            ),
                            array(
                                'key' => 'field_hero_hide',
                                'label' => 'Hide Block',
                                'name' => 'hide_block',
                                'type' => 'true_false',
                            ),
                        ),
                    ),
                    // ABOUT SECTION
                    'layout_65f1a2b3c4d60' => array(
                        'key' => 'layout_65f1a2b3c4d60',
                        'name' => 'home_about_block',
                        'label' => 'About Section',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_about_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                            ),
                            array(
                                'key' => 'field_about_text',
                                'label' => 'Content',
                                'name' => 'text',
                                'type' => 'wysiwyg',
                            ),
                            array(
                                'key' => 'field_about_image',
                                'label' => 'Side Image',
                                'name' => 'image',
                                'type' => 'image',
                                'return_format' => 'array',
                            ),
                            array(
                                'key' => 'field_about_features',
                                'label' => 'Features',
                                'name' => 'features',
                                'type' => 'repeater',
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_feature_title',
                                        'label' => 'Feature Title',
                                        'name' => 'title',
                                        'type' => 'text',
                                    ),
                                    array(
                                        'key' => 'field_feature_desc',
                                        'label' => 'Feature Description',
                                        'name' => 'description',
                                        'type' => 'textarea',
                                    ),
                                ),
                            ),
                            array(
                                'key' => 'field_about_hide',
                                'label' => 'Hide Block',
                                'name' => 'hide_block',
                                'type' => 'true_false',
                            ),
                        ),
                    ),
                    // PRODUCTS SECTION
                    'layout_65f1a2b3c4d61' => array(
                        'key' => 'layout_65f1a2b3c4d61',
                        'name' => 'product_listing_block',
                        'label' => 'Product Listing Section',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_prod_subtitle',
                                'label' => 'Subtitle',
                                'name' => 'subtitle',
                                'type' => 'text',
                            ),
                            array(
                                'key' => 'field_prod_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                            ),
                            array(
                                'key' => 'field_prod_desc',
                                'label' => 'Description',
                                'name' => 'description',
                                'type' => 'textarea',
                            ),
                            array(
                                'key' => 'field_prod_items',
                                'label' => 'Product Items',
                                'name' => 'product_items',
                                'type' => 'repeater',
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_item_title',
                                        'label' => 'Title',
                                        'name' => 'title',
                                        'type' => 'text',
                                    ),
                                    array(
                                        'key' => 'field_item_image',
                                        'label' => 'Image',
                                        'name' => 'image',
                                        'type' => 'image',
                                        'return_format' => 'array',
                                    ),
                                ),
                            ),
                            array(
                                'key' => 'field_prod_hide',
                                'label' => 'Hide Block',
                                'name' => 'hide_block',
                                'type' => 'true_false',
                            ),
                        ),
                    ),
                    // INDUSTRY SOLUTIONS
                    'layout_industry_solutions' => array(
                        'key' => 'layout_industry_solutions',
                        'name' => 'industry_solutions_block',
                        'label' => 'Industry Solutions',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_industry_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                            ),
                            array(
                                'key' => 'field_industry_hide',
                                'label' => 'Hide Block',
                                'name' => 'hide_block',
                                'type' => 'true_false',
                            ),
                        ),
                    ),
                    // CASE STUDIES
                    'layout_case_studies' => array(
                        'key' => 'layout_case_studies',
                        'name' => 'case_studies_block',
                        'label' => 'Case Studies Section',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_cs_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                            ),
                            array(
                                'key' => 'field_cs_hide',
                                'label' => 'Hide Block',
                                'name' => 'hide_block',
                                'type' => 'true_false',
                            ),
                        ),
                    ),
                    // TESTIMONIALS
                    'layout_testimonials' => array(
                        'key' => 'layout_testimonials',
                        'name' => 'testimonials_block',
                        'label' => 'Testimonials Section',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_test_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                            ),
                            array(
                                'key' => 'field_test_hide',
                                'label' => 'Hide Block',
                                'name' => 'hide_block',
                                'type' => 'true_false',
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_graphql' => true,
        'graphql_field_name' => 'homepageBlocks',
    ));

endif;
