"use client";

import React, { useState,  } from "react";
import ProductView from "@/components/ProductView";



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

export default async function Page({
    params,
}: {
    params: Promise<{ slug: string }>
}) {
    const { slug } = await params

    
      const [products, setProducts] = useState<Product[]>([]);
    // Find product by slug
    const product = products.find((p) => p.slug === slug);
    if (!product) {
        return (
            <div className="text-center py-8">
                <h1 className="text-3xl font-bold">Product Not Found</h1>
                <p>The product you&apos;re looking for is not available.</p>
            </div>
        );
    }

    // Return the ProductView component with the found product
    return <ProductView product={product} />;
}
