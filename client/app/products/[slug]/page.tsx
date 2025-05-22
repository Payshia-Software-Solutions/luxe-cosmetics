"use client";

import React, { useEffect, useState } from "react";
import ProductView from "@/components/ProductView";
import { Product } from "@/types/product"; // Adjust the import path as necessary
import {
  ProductReview,
  FormattedProduct,
  ProductSpecifications,
} from "@/types/ViewProduct";

const getValidImagePath = (imagePath: string): string => {
  if (!imagePath) {
    return "/images/placeholder.jpg";
  }

  if (imagePath.startsWith("http://") || imagePath.startsWith("https://")) {
    return imagePath;
  }

  if (imagePath.startsWith("/")) {
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
        // Fetch product data from your API endpoint
        const response = await fetch(
          `${process.env.NEXT_PUBLIC_API_URL}/products/get-by-slug/${slug}`  
        );

        if (!response.ok) {
          throw new Error("Failed to fetch product data");
        }

        const responseData = await response.json();
        // Extract the actual product data from the response
        const productData =
          responseData.data && responseData.data.length > 0
            ? responseData.data[0]
            : responseData;

        console.log("Raw API response:", responseData);
        console.log("Product data extracted:", productData);

        setProduct(productData);
        setIsLoading(false);
      } catch (err: unknown) {
        console.error("Error fetching product:", err);
        setError(
          err instanceof Error ? err.message : "An unknown error occurred"
        );
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
  let parsedReviews: ProductReview[] = [];
  try {
    parsedReviews = product.reviews ? JSON.parse(product.reviews) : [];
  } catch (e) {
    console.error("Error parsing reviews JSON:", e);
  }

  // Parse specifications JSON string
  const parsedSpecifications: ProductSpecifications = {};
  try {
    if (product.specifications) {
      console.log("Raw specifications:", product.specifications);

      const specs =
        typeof product.specifications === "string"
          ? JSON.parse(product.specifications)
          : product.specifications;

      // Handle ingredients
      if (specs.ingredients) {
        parsedSpecifications.ingredients = Array.isArray(specs.ingredients)
          ? specs.ingredients
          : [specs.ingredients];
      }

      // Handle skin_type
      if (specs.skin_type) {
        parsedSpecifications.skin_type = Array.isArray(specs.skin_type)
          ? specs.skin_type
          : [specs.skin_type];
      }

      // Handle all other fields
      Object.keys(specs).forEach((key) => {
        if (key !== "ingredients" && key !== "skin_type") {
          parsedSpecifications[key] = specs[key];
        }
      });

      console.log("Parsed and formatted specifications:", parsedSpecifications);
    } else {
      console.log("No specifications data available");
    }
  } catch (e) {
    console.error("Error parsing specifications JSON:", e);
  }

  // Map API response to match the expected format for ProductView component
  const formattedProduct: FormattedProduct = {
    id: product.product_id,
    name: product.product_name,
    slug: product.slug,
    category: product.category || "",
    price: product.selling_price,
    rating: parseFloat(product.rating) || 0,
    reviews: parsedReviews.map((review: ProductReview, index: number) => ({
      id: review.id || index + 1, // Generate ID if not provided
      user: review.user,
      rating: review.rating,
      comment: review.comment,
      date: review.timestamp || new Date().toISOString().split("T")[0],
      verified: review.verified !== undefined ? review.verified : true,
      helpful: review.helpful || 0,
      title: review.title || "Review",
    })),
    description: product.product_description || "",
    longDescription: product.long_description || "",
    benefits: product.benefits
      ? product.benefits.split(",").map((benefit: string) => benefit.trim())
      : [],
    specifications: parsedSpecifications,
    images: [
      getValidImagePath(product.image_path),
      getValidImagePath(product.hover_image || ""),
      // Add placeholder images if needed
      "/images/placeholder1.jpg",
      "/images/placeholder2.jpg",
    ].filter(Boolean),
    breadcrumbs: ["Home", product.category || "Products", product.product_name],
  };

  console.log("Product data:", formattedProduct);
  return <ProductView product={formattedProduct} />;
}