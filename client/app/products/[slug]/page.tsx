"use client";

import React, { useEffect, useState } from 'react';
import ProductView from "@/components/ProductView";
import { Metadata } from 'next';

// export const metadata: Metadata = {
//     title: "Cosmetic Shop | Premium Beauty & Skincare Products Online",
//     description: "Discover a wide range of premium beauty and skincare products at our Cosmetic Shop. Shop for makeup, skincare, haircare, and more with fast delivery and expert advice.",
//     keywords: "cosmetic shop, beauty products, skincare, makeup, skincare products, premium cosmetics, online beauty store, skincare online, makeup online, beauty essentials",
//     robots: "index, follow",
//     viewport: "width=device-width, initial-scale=1",
// };

// Define the type for your API response based on the sample data
interface ProductData {
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
  } | string; // Handle both parsed object and string JSON
  category: string;
  meta_description: string;
  reviews: string; // JSON string
  hover_image: string;
}

// Define the type expected by your ProductView component (based on the Product interface in ProductView)
interface FormattedProduct {
  id: number;
  name: string;
  slug: string;
  category: string;
  price: number;
  rating: number;
  reviews: Array<{
    id?: number;
    user: string;
    rating: number;
    title?: string;
    comment: string;
    date?: string;
    timestamp?: string;
    verified?: boolean;
    helpful?: number;
  }>;
  description: string;
  longDescription: string;
  benefits: string[];
  specifications: Record<string, any>;
  images: string[];
  breadcrumbs: string[];
}

// Helper function to ensure image paths are valid
const getValidImagePath = (imagePath: string): string => {
  if (!imagePath) {
    return '/images/placeholder.jpg'; // Fallback image
  }
  
  // If it's already a full URL, return as is
  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    return imagePath;
  }
  
  // If it starts with a slash, it's already a valid local path
  if (imagePath.startsWith('/')) {
    return imagePath;
  }
  
  // Otherwise, make it a valid local path by adding a slash
  return `/${imagePath}`;
};

export default function Page({
    params,
}: {
    params: Promise<{ slug: string }> // Note the Promise type here
}) {
    // Unwrap the params Promise using React.use()
    const resolvedParams = React.use(params);
    const { slug } = resolvedParams;
    
    const [product, setProduct] = useState<ProductData | null>(null);
    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const fetchProductData = async () => {
            try {
                setIsLoading(true);
                // Fetch product data from your API endpoint
                const response = await fetch(`http://localhost/luxe-cosmetics/server/products/get-by-slug/${slug}`);
                
                if (!response.ok) {
                    throw new Error('Failed to fetch product data');
                }
                
                const data: ProductData = await response.json();
                setProduct(data);
                setIsLoading(false);
            } catch (err: any) {
                console.error('Error fetching product:', err);
                setError(err.message);
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

    // Parse reviews JSON string
    let parsedReviews = [];
    try {
        parsedReviews = product.reviews ? JSON.parse(product.reviews) : [];
    } catch (e) {
        console.error('Error parsing reviews JSON:', e);
    }

    // Parse specifications if it's a string
    let parsedSpecifications = {};
    if (typeof product.specifications === 'string') {
        try {
            parsedSpecifications = JSON.parse(product.specifications);
        } catch (e) {
            console.error('Error parsing specifications JSON:', e);
        }
    } else {
        // It's already an object
        parsedSpecifications = product.specifications;
    }

    // Map API response to match the expected format for ProductView component
    const formattedProduct: FormattedProduct = {
        id: product.product_id,
        name: product.product_name,
        slug: product.slug,
        category: product.category || '',
        price: product.selling_price,
        rating: parseFloat(product.rating) || 0,
        reviews: parsedReviews.map((review: any, index: number) => ({
            id: index + 1,  // Generate ID if not provided
            ...review,
            date: review.timestamp || new Date().toISOString().split('T')[0],
            verified: review.verified !== undefined ? review.verified : true,
            helpful: review.helpful || 0,
            title: review.title || "Review"
        })),
        description: product.product_description || '',
        longDescription: product.long_description || '',
        benefits: product.benefits ? product.benefits.split(',').map((benefit: string) => benefit.trim()) : [],
        specifications: parsedSpecifications,
        images: [
            getValidImagePath(product.image_path),
            getValidImagePath(product.hover_image) || getValidImagePath(product.image_path),
            // Add placeholder images if needed
            '/images/placeholder1.jpg',
            '/images/placeholder2.jpg',
        ].filter(Boolean),
        breadcrumbs: ['Home', product.category || 'Products', product.product_name]
    };

    return <ProductView product={formattedProduct} />;
}