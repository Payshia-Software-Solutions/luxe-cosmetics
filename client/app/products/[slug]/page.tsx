"use client";

import React, { useEffect, useState } from 'react';
import ProductView from "@/components/ProductView";
import { Product } from "@/types/product";
import { ProductSpecifications } from "@/types/ProductSpecifications";

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

    const [product, setProduct] = useState<Product | null>(null);
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

                const data: Product = await response.json();
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

    // Enhance product with parsed data for the view
    // We're keeping the original Product type but adding computed properties
    const enhancedProduct = {
        ...product,
        // Add parsed reviews to the product
        parsedReviews: (() => {
            try {
                return product.reviews ? JSON.parse(product.reviews) as ReviewType[] : [];
            } catch (e) {
                console.error('Error parsing reviews JSON:', e);
                return [];
            }
        })(),
        // Add parsed specifications to the product
        parsedSpecifications: (() => {
            if (typeof product.specifications === 'string') {
                try {
                    return JSON.parse(product.specifications) as ProductSpecifications;
                } catch (e) {
                    console.error('Error parsing specifications JSON:', e);
                    return {};
                }
            } else if (product.specifications) {
                return product.specifications as unknown as ProductSpecifications;
            }
            return {};
        })(),
        // Add formatted images array
        images: [
            getValidImagePath(product.image_path),
            getValidImagePath(product.hover_image || ''),
            '/images/placeholder1.jpg',
            '/images/placeholder2.jpg',
        ].filter(Boolean),
        // Add benefits array
        benefitsArray: product.benefits
            ? product.benefits.split(',').map((benefit: string) => benefit.trim())
            : [],
        // Add breadcrumbs
        breadcrumbs: ['Home', product.category || 'Products', product.product_name]
    };

    return <ProductView product={enhancedProduct} />;
}