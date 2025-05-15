"use client";

import React, { useEffect, useState } from 'react';
import ProductView from "@/components/ProductView";
<<<<<<< Updated upstream
<<<<<<< Updated upstream
=======
import { ProductData } from "@/types/ProductData";
import { ProductSpecifications } from "@/types/ProductSpecifications";
import { ProductReview } from "@/types/ProductReview";
import { FormattedProduct } from '@/types/FormattedProduct';
>>>>>>> Stashed changes
// import { Metadata } from 'next';
=======

// Uncomment this block if you plan to export metadata
/*
import { Metadata } from 'next';
>>>>>>> Stashed changes

<<<<<<< Updated upstream
export const metadata: Metadata = {
  title: "Cosmetic Shop | Premium Beauty & Skincare Products Online",
  description: "Discover a wide range of premium beauty and skincare products at our Cosmetic Shop. Shop for makeup, skincare, haircare, and more with fast delivery and expert advice.",
  keywords: "cosmetic shop, beauty products, skincare, makeup, skincare products, premium cosmetics, online beauty store, skincare online, makeup online, beauty essentials",
  robots: "index, follow",
  viewport: "width=device-width, initial-scale=1",
};
*/

// API response type definition
interface ProductData {
<<<<<<< Updated upstream
  product_id: number;
  product_code: string;
  product_name: string;
  slug: string;
  display_name: string;
  name_si: string;
  name_ti: string;
  print_name: string;
  section_id: number;
  department_id: number;
  category_id: number;
  brand_id: number;
  measurement: string;
  reorder_level: number;
  lead_days: number;
  cost_price: number;
  selling_price: number;
  minimum_price: number;
  wholesale_price: number;
  price_2: number;
  item_type: string;
  item_location: string;
  image_path: string;
  created_by: string;
  created_at: string;
  active_status: number;
  generic_id: number;
  supplier_list: string;
  size_id: number;
  color_id: number;
  product_description: string;
  how_to_use: string | null;
  recipe_type: string;
  barcode: string;
  expiry_good: number;
  location_list: string;
  opening_stock: number;
  special_promo: number;
  special_promo_type: string;
  special_promo_message: string | null;
  rating: string;
  review: number;
  long_description: string;
  benefits: string;
  specifications: {
    ingredients: string[];
    skin_type: string[];
  } | string; 
  category: string;
  meta_description: string;
  reviews: string; 
  hover_image: string;
}


interface ProductSpecifications {
  ingredients?: string[];
  skin_type?: string[];
  [key: string]: string[] | string | undefined; 
}

interface ProductReview {
  id?: number;
  user: string;
  rating: number;
  title?: string;
  comment: string;
  date?: string;
  timestamp?: string;
  verified?: boolean;
  helpful?: number;
}

interface FormattedProduct {
  id: number;
  name: string;
  slug: string;
  category: string;
  price: number;
  rating: number;
  reviews: ProductReview[];
  description: string;
  longDescription: string;
  benefits: string[];
  specifications: ProductSpecifications;
  images: string[];
  breadcrumbs: string[];
}
=======
// Define the type for your API response based on the sample data
>>>>>>> Stashed changes

export interface Product {
    id: number;
    slug: string;
    name: string;
    price: number;
    rating: number;
    review: number;
    description: string;
    longDescription: string;
    benefits: string[];
    specifications: Record<string, string>;
    ingredients: string;
    images: string[];
    category: string;
    breadcrumbs: string[];
    metaDescription: string;
    reviews: Review[];
}

export interface Review {
    id: number;
    user: string;
    rating: number;
    date: string;
    title: string;
    comment: string;
    verified: boolean;
    helpful: number;
}

const getValidImagePath = (imagePath: string): string => {
    if (!imagePath) {
        return '/images/placeholder.jpg';
    }

<<<<<<< Updated upstream
  return `/${imagePath}`;
=======
    product_id: number;
    product_code: string;
    product_name: string;
    slug: string;
    display_name: string;
    name_si: string;
    name_ti: string;
    print_name: string;
    section_id: number;
    department_id: number;
    category_id: number;
    brand_id: number;
    measurement: string;
    reorder_level: number;
    lead_days: number;
    cost_price: number;
    selling_price: number;
    minimum_price: number;
    wholesale_price: number;
    price_2: number;
    item_type: string;
    item_location: string;
    image_path: string;
    created_by: string;
    created_at: string;
    active_status: number;
    generic_id: number;
    supplier_list: string;
    size_id: number;
    color_id: number;
    product_description: string;
    how_to_use: string | null;
    recipe_type: string;
    barcode: string;
    expiry_good: number;
    location_list: string;
    opening_stock: number;
    special_promo: number;
    special_promo_type: string;
    special_promo_message: string | null;
    rating: string;
    review: number;
    long_description: string;
    benefits: string;
    specifications: {
        ingredients: string[];
        skin_type: string[];
    } | string;
    category: string;
    meta_description: string;
    reviews: string;
    hover_image: string;
}

// Type for review object
type ReviewType = {
    id?: number;
    user: string;
    rating: number;
    title?: string;
    comment: string;
    date?: string;
    timestamp?: string;
    verified?: boolean;
    helpful?: number;
};

// Product prop shape expected by ProductView
interface FormattedProduct {
    id: number;
    name: string;
    slug: string;
    category: string;
    price: number;
    rating: number;
    reviews: ReviewType[];
    description: string;
    longDescription: string;
    benefits: string[];
    specifications: Record<string, unknown>;
    images: string[];
    breadcrumbs: string[];
}

// Normalize image path
const getValidImagePath = (imagePath: string): string => {
    if (!imagePath) return '/images/placeholder.jpg';

    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) return imagePath;

    if (imagePath.startsWith('/')) return imagePath;

    return `/${imagePath}`;
>>>>>>> Stashed changes
=======
    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
        return imagePath;
    }


    if (imagePath.startsWith('/')) {
        return imagePath;
    }


    return `/${imagePath}`;
>>>>>>> Stashed changes
};

export default function Page({
    params,
}: {
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    params: Promise<{ slug: string }> 
=======
    params: Promise<{ slug: string }>
>>>>>>> Stashed changes
}) {


=======
    params: Promise<{ slug: string }>;
}) {
>>>>>>> Stashed changes
    const resolvedParams = React.use(params);
    const { slug } = resolvedParams;

    const [product, setProduct] = useState<ProductData | null>(null);
    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const fetchProductData = async () => {
            try {
                setIsLoading(true);
                const response = await fetch(`http://localhost/luxe-cosmetics/server/products/get-by-slug/${slug}`);
<<<<<<< Updated upstream
=======

>>>>>>> Stashed changes
                if (!response.ok) {
                    throw new Error('Failed to fetch product data');
                }

                const data: ProductData = await response.json();
                setProduct(data);
                setIsLoading(false);
<<<<<<< Updated upstream
            } catch (err: unknown) { // Using unknown instead of any
                console.error('Error fetching product:', err);
                setError(err instanceof Error ? err.message : 'An unknown error occurred');
=======
            } catch (err: unknown) {
                // Safe narrowing for unknown error
                if (err instanceof Error) {
                    console.error('Error fetching product:', err);
                    setError(err.message);
                } else {
                    setError('An unknown error occurred');
                }
>>>>>>> Stashed changes
                setIsLoading(false);
            }
        };

        if (slug) {
            fetchProductData();
        }
    }, [slug]);

    if (isLoading) {
        return (
            <div className="flex justify-center items-center min-h-[50vh]">
                <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-pink-600"></div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="text-center py-8">
                <h1 className="text-3xl font-bold">Error</h1>
                <p>Failed to load product: {error}</p>
            </div>
        );
    }

    if (!product) {
        return (
            <div className="text-center py-8">
                <h1 className="text-3xl font-bold">Product Not Found</h1>
                <p>The product you&apos;re looking for is not available.</p>
            </div>
        );
    }

<<<<<<< Updated upstream
    // Parse reviews JSON string
    let parsedReviews: ProductReview[] = [];
=======
    // Parse reviews safely
    let parsedReviews: ReviewType[] = [];
>>>>>>> Stashed changes
    try {
        parsedReviews = product.reviews ? JSON.parse(product.reviews) as ReviewType[] : [];
    } catch (e) {
        console.error('Error parsing reviews JSON:', e);
    }

<<<<<<< Updated upstream
    // Parse specifications if it's a string
    let parsedSpecifications: ProductSpecifications = {};
=======
    // Parse specifications safely
    let parsedSpecifications: Record<string, unknown> = {};
>>>>>>> Stashed changes
    if (typeof product.specifications === 'string') {
        try {
            parsedSpecifications = JSON.parse(product.specifications);
        } catch (e) {
            console.error('Error parsing specifications JSON:', e);
        }
    } else {
        parsedSpecifications = product.specifications;
    }

    // Format product object for ProductView
    const formattedProduct: FormattedProduct = {
        id: product.product_id,
        name: product.product_name,
        slug: product.slug,
        category: product.category || '',
        price: product.selling_price,
        rating: parseFloat(product.rating) || 0,
<<<<<<< Updated upstream
        reviews: parsedReviews.map((review: ProductReview, index: number) => ({
            id: review.id || index + 1,  // Generate ID if not provided
            user: review.user,
            rating: review.rating,
            comment: review.comment,
=======
        reviews: parsedReviews.map((review: ReviewType, index: number) => ({
            id: index + 1,
            ...review,
>>>>>>> Stashed changes
            date: review.timestamp || new Date().toISOString().split('T')[0],
            verified: review.verified !== undefined ? review.verified : true,
            helpful: review.helpful || 0,
            title: review.title || "Review"
        })),
        description: product.product_description || '',
        longDescription: product.long_description || '',
        benefits: product.benefits
            ? product.benefits.split(',').map((benefit: string) => benefit.trim())
            : [],
        specifications: parsedSpecifications,
        images: [
            getValidImagePath(product.image_path),
            getValidImagePath(product.hover_image) || getValidImagePath(product.image_path),
            '/images/placeholder1.jpg',
            '/images/placeholder2.jpg',
        ].filter(Boolean),
        breadcrumbs: ['Home', product.category || 'Products', product.product_name]
    };

    return <ProductView product={formattedProduct} />;
}
