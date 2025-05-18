"use client";

import React, { useEffect, useState } from 'react';
import ProductView from "@/components/ProductView";
import { ProductData } from "@/types/ProductData";
// import { ProductSpecifications } from "@/types/ProductSpecifications";
// import { ProductReview } from "@/types/ProductReview";
import { FormattedProduct } from '@/types/FormattedProduct';

// Uncomment this block if you plan to export metadata
/*
import { Metadata } from 'next';

export const metadata: Metadata = {
  title: "Cosmetic Shop | Premium Beauty & Skincare Products Online",
  description: "Discover a wide range of premium beauty and skincare products at our Cosmetic Shop. Shop for makeup, skincare, haircare, and more with fast delivery and expert advice.",
  keywords: "cosmetic shop, beauty products, skincare, makeup, skincare products, premium cosmetics, online beauty store, skincare online, makeup online, beauty essentials",
  robots: "index, follow",
  viewport: "width=device-width, initial-scale=1",
};
*/

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

    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
        return imagePath;
    }

    if (imagePath.startsWith('/')) {
        return imagePath;
    }

    return `/${imagePath}`;
};

export default function Page({
    params,
}: {
    params: Promise<{ slug: string }>;
}) {
    const resolvedParams = React.use(params);
    const { slug } = resolvedParams;

    const [product, setProduct] = useState<ProductData | null>(null);
    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const fetchProductData = async () => {
            try {
                setIsLoading(true);
                const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/products/get-by-slug/${slug}`);

                if (!response.ok) {
                    throw new Error('Failed to fetch product data');
                }

                const data: ProductData = await response.json();
                setProduct(data);
                setIsLoading(false);
            } catch (err: unknown) {
                // Safe narrowing for unknown error
                if (err instanceof Error) {
                    console.error('Error fetching product:', err);
                    setError(err.message);
                } else {
                    setError('An unknown error occurred');
                }
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

    // Parse reviews safely
    let parsedReviews: ReviewType[] = [];
    try {
        parsedReviews = product.reviews ? JSON.parse(product.reviews) as ReviewType[] : [];
    } catch (e) {
        console.error('Error parsing reviews JSON:', e);
    }

    // Parse specifications safely
    let parsedSpecifications: Record<string, unknown> = {};
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
        reviews: parsedReviews.map((review: ReviewType, index: number) => ({
            id: index + 1,
            ...review,
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