<?php
/**
 * Settings configuration.
 *
 * @package HivePress\Configs
 */

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'requests' => [
		'title'    => esc_html__( 'Requests', 'hivepress-requests' ),
		'_order'   => 120,

		'sections' => [
			'display'    => [
				'title'  => hivepress()->translator->get_string( 'display_noun' ),
				'_order' => 10,

				'fields' => [
					'page_requests'     => [
						'label'       => esc_html__( 'Requests Page', 'hivepress-requests' ),
						'description' => esc_html__( 'Choose a page that displays all requests.', 'hivepress-requests' ),
						'type'        => 'select',
						'options'     => 'posts',
						'option_args' => [ 'post_type' => 'page' ],
						'_order'      => 10,
					],

					'requests_per_page' => [
						'label'     => esc_html__( 'Requests per Page', 'hivepress-requests' ),
						'type'      => 'number',
						'default'   => 10,
						'min_value' => 1,
						'required'  => true,
						'_order'    => 20,
					],
				],
			],

			'submission' => [
				'title'  => hivepress()->translator->get_string( 'submission' ),
				'_order' => 20,

				'fields' => [
					'request_allow_sending'     => [
						'label'   => hivepress()->translator->get_string( 'submission' ),
						'caption' => esc_html__( 'Allow sending personal requests', 'hivepress-requests' ),
						'type'    => 'checkbox',
						'_order'  => 10,
					],

					'request_enable_moderation' => [
						'label'   => hivepress()->translator->get_string( 'moderation' ),
						'caption' => esc_html__( 'Manually approve new requests', 'hivepress-requests' ),
						'type'    => 'checkbox',
						'default' => true,
						'_order'  => 20,
					],

					'request_notify_vendors'    => [
						'label'   => hivepress()->translator->get_string( 'emails' ),
						'caption' => hivepress()->translator->get_string( 'notify_vendors_about_requests' ),
						'type'    => 'checkbox',
						'_order'  => 30,
					],
				],
			],

			'expiration' => [
				'title'  => hivepress()->translator->get_string( 'expiration' ),
				'_order' => 30,

				'fields' => [
					'request_expiration_period' => [
						'label'       => hivepress()->translator->get_string( 'expiration_period' ),
						'description' => esc_html__( 'Set the number of days after which a request expires.', 'hivepress-requests' ),
						'type'        => 'number',
						'min_value'   => 1,
						'_order'      => 10,
					],
				],
			],

			'emails'     => [
				'title'  => hivepress()->translator->get_string( 'emails' ),
				'_order' => 1000,

				'fields' => [
					'email_request_approve' => [
						'label'       => esc_html__( 'Request Approved', 'hivepress-requests' ),
						'description' => esc_html__( 'This email is sent to users when a request is approved.', 'hivepress-requests' ) . ' ' . sprintf( hivepress()->translator->get_string( 'these_tokens_are_available' ), '%user_name%, %request_title%, %request_url%' ),
						'type'        => 'textarea',
						'default'     => hp\sanitize_html( __( 'Hi, %user_name%! Your request "%request_title%" has been approved, click on the following link to view it: %request_url%', 'hivepress-requests' ) ),
						'max_length'  => 2048,
						'html'        => true,
						'_autoload'   => false,
						'_parent'     => 'request_enable_moderation',
						'_order'      => 10,
					],

					'email_request_reject'  => [
						'label'       => esc_html__( 'Request Rejected', 'hivepress-requests' ),
						'description' => esc_html__( 'This email is sent to users when a request is rejected.', 'hivepress-requests' ) . ' ' . sprintf( hivepress()->translator->get_string( 'these_tokens_are_available' ), '%user_name%, %request_title%' ),
						'type'        => 'textarea',
						'default'     => hp\sanitize_html( __( 'Hi, %user_name%! Unfortunately, your request "%request_title%" has been rejected.', 'hivepress-requests' ) ),
						'max_length'  => 2048,
						'html'        => true,
						'_autoload'   => false,
						'_parent'     => 'request_enable_moderation',
						'_order'      => 20,
					],

					'email_request_expire'  => [
						'label'       => esc_html__( 'Request Expired', 'hivepress-requests' ),
						'description' => esc_html__( 'This email is sent to users when a request is expired.', 'hivepress-requests' ) . ' ' . sprintf( hivepress()->translator->get_string( 'these_tokens_are_available' ), '%user_name%, %request_title%' ),
						'type'        => 'textarea',
						'default'     => hp\sanitize_html( __( 'Hi, %user_name%! Your request "%request_title%" has expired.', 'hivepress-requests' ) ),
						'max_length'  => 2048,
						'html'        => true,
						'_autoload'   => false,
						'_order'      => 30,
					],

					'email_request_send'    => [
						'label'       => esc_html__( 'Request Received', 'hivepress-requests' ),
						'description' => esc_html__( 'This email is sent to users when a personal request is received.', 'hivepress-requests' ) . ' ' . sprintf( hivepress()->translator->get_string( 'these_tokens_are_available' ), '%user_name%, %request_title%, %request_url%' ),
						'type'        => 'textarea',
						'default'     => hp\sanitize_html( __( 'Hi, %user_name%! You\'ve received a new request "%request_title%", click on the following link to view it: %request_url%', 'hivepress-requests' ) ),
						'max_length'  => 2048,
						'html'        => true,
						'_autoload'   => false,
						'_parent'     => 'request_allow_sending',
						'_order'      => 40,
					],

					'email_request_find'    => [
						'label'       => esc_html__( 'Requests Available', 'hivepress-requests' ),
						'description' => esc_html__( 'This email is sent to users when there are new requests available.', 'hivepress-requests' ) . ' ' . sprintf( hivepress()->translator->get_string( 'these_tokens_are_available' ), '%user_name%, %requests_url%' ),
						'type'        => 'textarea',
						'default'     => hp\sanitize_html( __( 'Hi, %user_name%! There are new requests available, click on the following link to view them: %requests_url%', 'hivepress-requests' ) ),
						'max_length'  => 2048,
						'html'        => true,
						'_autoload'   => false,
						'_parent'     => 'request_notify_vendors',
						'_order'      => 50,
					],
				],
			],
		],
	],

	'offers'   => [
		'title'    => esc_html__( 'Offers', 'hivepress-requests' ),
		'_order'   => 125,

		'sections' => [
			'display'    => [
				'title'  => hivepress()->translator->get_string( 'display_noun' ),
				'_order' => 10,

				'fields' => [
					'offer_display_restriction' => [
						'label'       => hivepress()->translator->get_string( 'display_noun' ),
						'description' => esc_html__( 'Select which users can view the submitted offers.', 'hivepress-requests' ),
						'type'        => 'select',
						'placeholder' => hivepress()->translator->get_string( 'all_users' ),
						'_order'      => 10,

						'options'     => [
							'users'  => hivepress()->translator->get_string( 'registered_users' ),
							'author' => esc_html__( 'Request Author', 'hivepress-requests' ),
						],
					],
				],
			],

			'submission' => [
				'title'  => hivepress()->translator->get_string( 'submission' ),
				'_order' => 20,

				'fields' => [
					'offer_allow_multiple'    => [
						'label'   => hivepress()->translator->get_string( 'submission' ),
						'caption' => esc_html__( 'Allow submitting multiple offers', 'hivepress-requests' ),
						'type'    => 'checkbox',
						'_order'  => 10,
					],

					'offer_enable_moderation' => [
						'label'   => hivepress()->translator->get_string( 'moderation' ),
						'caption' => esc_html__( 'Manually approve new offers', 'hivepress-requests' ),
						'type'    => 'checkbox',
						'default' => true,
						'_order'  => 20,
					],

					'offer_allow_bidding'     => [
						'label'   => esc_html__( 'Bidding', 'hivepress-requests' ),
						'caption' => esc_html__( 'Allow bidding on requests', 'hivepress-requests' ),
						'type'    => 'checkbox',
						'default' => true,
						'_order'  => 30,
					],
				],
			],

			'emails'     => [
				'title'  => hivepress()->translator->get_string( 'emails' ),
				'_order' => 1000,

				'fields' => [
					'email_offer_make' => [
						'label'       => esc_html__( 'Offer Received', 'hivepress-requests' ),
						'description' => esc_html__( 'This email is sent to users when a new offer is received.', 'hivepress-requests' ) . ' ' . sprintf( hivepress()->translator->get_string( 'these_tokens_are_available' ), '%user_name%, %offer_url%' ),
						'type'        => 'textarea',
						'default'     => hp\sanitize_html( __( "Hi, %user_name%! You've received a new offer, click on the following link to view it: %offer_url%", 'hivepress-requests' ) ),
						'max_length'  => 2048,
						'html'        => true,
						'_autoload'   => false,
						'_order'      => 10,
					],
				],
			],
		],
	],
];
