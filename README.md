# Canonical URL GraphQl module for Magento 2 by Ecwhim

This module provides support of Magento GraphQL for [Magento 2 Canonical URL Extension](https://www.ecwhim.com/magento-2-canonical-url-extension.html) by Ecwhim.

## Installation

You can install the module in the following ways:

1. [Install the module using Composer via packagist.com](#install-the-module-using-composer-via-packagistcom).
2. [Download package from github.com](#download-package-from-githubcom).

### Install the module using Composer via packagist.com

1. Log in to your Magento server as, or switch to, the file system owner.
2. Navigate to your Magento project directory.
3. Get the latest version of the module:
```shell
composer require ecwhim/module-canonical-url-graph-gl
```
4. [Enable the module](https://docs.ecwhim.com/Installation/#enable-the-extension).

### Download package from github.com

1. Download the latest version of the [package from github](https://github.com/ecwhim/magento2-canonical-url-graph-ql).
2. Extract the package to the `<magento root directory>/app/code/Ecwhim/CanonicalUrlGraphQl` directory.
3. [Enable the module](https://docs.ecwhim.com/Installation/#enable-the-extension).

## How to use

### CategoryInterface attribute

The following table defines the CategoryInterface attribute added by our extension.

| ATTRIBUTE         | DATA TYPE | DESCRIPTION |
| ----------------- | --------- | ----------- |
| ecw_canonical_url | String    | The absolute canonical URL. |

#### Example Usage

The following query shows how to get the canonical URL for a category:

**Request:**
```graphql
{
    categoryList(filters: {ids: {eq: "14"}}){
        id
        name
        ecw_canonical_url
    }
}
```

**Response:**
```json
{
    "data": {
        "categoryList": [
            {
                "id": 14,
                "name": "Jackets",
                "ecw_canonical_url": "https://example.com/men/tops-men/jackets-men.html"
            }
        ]
    }
}
```

### CmsPage attribute

The following table defines the CmsPage attribute added by our extension.

| ATTRIBUTE         | DATA TYPE | DESCRIPTION |
| ----------------- | --------- | ----------- |
| ecw_canonical_url | String    | The absolute canonical URL. |

#### Example Usage

The following query shows how to get the canonical URL for a CMS page:

**Request:**
```graphql
{
    cmsPage(identifier: "customer-service") {
        identifier
        title
        ecw_canonical_url
    }
}
```

**Response:**
```json
{
    "data": {
        "cmsPage": {
            "identifier": "customer-service",
            "title": "Customer Service",
            "ecw_canonical_url": "https://example.com/customer-service"
        }
    }
}
```

### ProductInterface attribute

The following table defines the ProductInterface attribute added by our extension.

| ATTRIBUTE         | DATA TYPE | DESCRIPTION |
| ----------------- | --------- | ----------- |
| ecw_canonical_url | String    | The absolute canonical URL. |

#### Example Usage

The following query shows how to get the canonical URL for a product:

**Request:**
```graphql
{
    products(filter: { sku: { eq: "24-MB01" } }) {
        items {
            name
            sku
            ecw_canonical_url
        }
    }
}
```

**Response:**
```json
{
    "data": {
        "products": {
            "items": [
                {
                    "name": "Joust Duffle Bag",
                    "sku": "24-MB01",
                    "ecw_canonical_url": "https://example.com/joust-duffle-bag.html"
                }
            ]
        }
    }
}
```
