<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */
namespace TemplateMonster\ProductLabels\Api\Data;

interface ProductLabelInterface
{

    const REGISTRY_NAME = 'product_label';

    const NAME = 'name';

    const PRIORITY = 'priority';

    const HIGHER_PRIORITY = 'higher_priority';

    const USE_FOR_PARENT = 'use_for_parent';

    const WEBSITE_IDS = 'website_ids';

    const PRODUCT_LABEL_STATUS = 'product_label_status';

    const PRODUCT_LABEL_TYPE = 'product_label_type';

    const PRODUCT_IMAGE_LABEL = 'product_image_label';

    const PRODUCT_IMAGE_POSITION = 'product_image_position';

    const PRODUCT_IMAGE_CONTAINER = 'product_image_container';

    const PRODUCT_IMAGE_WIDTH = 'product_image_width';

    const PRODUCT_IMAGE_HEIGHT = 'product_image_height';

    const PRODUCT_IMAGE_CSS = 'product_image_css';

    const PRODUCT_TEXT_BACKGROUND = 'product_text_background';

    const PRODUCT_TEXT_COMMENT = 'product_text_comment';

    const PRODUCT_TEXT_LABEL_POSITION = 'product_text_label_position';

    const PRODUCT_TEXT_FONTSIZE = 'product_text_fontsize';

    const PRODUCT_TEXT_FONTCOLOR = 'product_text_fontcolor';

    const PRODUCT_TEXT_POSITION = 'product_text_position';

    const PRODUCT_TEXT_CONTAINER = 'product_text_container';

    const PRODUCT_TEXT_WIDTH = 'product_text_width';

    const PRODUCT_TEXT_HEIGHT = 'product_text_height';

    const PRODUCT_TEXT_CSS = 'product_text_css';

    const CATEGORY_LABEL_STATUS = 'category_label_status';

    const CATEGORY_LABEL_TYPE = 'category_label_type';

    const CATEGORY_IMAGE_LABEL = 'category_image_label';

    const CATEGORY_IMAGE_POSITION = 'category_image_position';

    const CATEGORY_IMAGE_CONTAINER = 'category_image_container';

    const CATEGORY_IMAGE_WIDTH = 'category_image_width';

    const CATEGORY_IMAGE_HEIGHT = 'category_image_height';

    const CATEGORY_IMAGE_CSS = 'category_image_css';

    const CATEGORY_TEXT_BACKGROUND = 'category_text_background';

    const CATEGORY_TEXT_COMMENT = 'category_text_comment';

    const CATEGORY_TEXT_LABEL_POSITION = 'category_text_label_position';

    const CATEGORY_TEXT_FONTSIZE = 'category_text_fontsize';

    const CATEGORY_TEXT_FONTCOLOR = 'category_text_fontcolor';

    const CATEGORY_TEXT_POSITION = 'category_text_position';

    const CATEGORY_TEXT_CONTAINER = 'category_text_container';

    const CATEGORY_TEXT_WIDTH = 'category_text_width';

    const CATEGORY_TEXT_HEIGHT = 'category_text_height';

    const CATEGORY_TEXT_CSS = 'category_text_css';

    const CONDITIONS_SERIALIZED = 'conditions_serialized';

    const USE_DATE_RANGE = 'use_date_range';

    const FROM_DATE = 'from_date';

    const FROM_TIME = 'from_time';

    const TO_DATE = 'to_date';

    const TO_TIME = 'to_time';

    const IS_NEW = 'is_new';

    const IS_ON_SALE = 'is_on_sale';

    const STOCK_STATUS = 'stock_status';

    const USE_PRICE_RANGE = 'use_price_range';

    const BY_PRICE = 'by_price';

    const FROM_PRICE = 'from_price';

    const TO_PRICE = 'to_price';

    const USE_CUSTOMER_GROUP = 'use_customer_group';

    const CUSTOMER_GROUP_IDS = 'customer_group_ids';
}
