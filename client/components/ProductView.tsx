"use client";
import React, { useState, useEffect } from 'react';
import { Star, Heart, Share2, Minus, Plus, ShoppingCart, ChevronRight } from 'lucide-react';
import { toast, ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

import type { Product } from '@/data/products';
import { products } from '@/data/products';

import Link from 'next/link';
import Image from 'next/image';
import RelatedProducts from './RelatedProducts';

interface ProductViewProps {
  product: Product;
}

interface CartItem {
  id: string;
  name: string;
  price: number;
  quantity: number;
  image: string;
}

const getValidImagePath = (imagePath: string): string => {
  let finalPath;
  
  if (!imagePath) {
    finalPath = '/assets/placeholder.jpg';
  }
  else if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    finalPath = imagePath;
  }
  else if (imagePath.startsWith('/assets/product/')) {
    finalPath = imagePath;
  }
  else if (imagePath.startsWith('/')) {
    finalPath = `/assets/product${imagePath}`;
  }
  else {
    finalPath = `/assets/product/${imagePath}`;
  }
  
  // Log the original and transformed image path for debugging
  console.log(`Image path transformation: ${imagePath} → ${finalPath}`);
  
  return finalPath;
};

export default function ProductView({ product }: ProductViewProps) {
  const [quantity, setQuantity] = useState(1);
  const [selectedImage, setSelectedImage] = useState(0);
  const [activeTab, setActiveTab] = useState('description');
  const [imageError, setImageError] = useState<Record<number, boolean>>({});
  const [cartItems, setCartItems] = useState<CartItem[]>([]);

  // Load cart items from local storage on component mount
  useEffect(() => {
    const storedCart = localStorage.getItem('cart');
    if (storedCart) {
      try {
        setCartItems(JSON.parse(storedCart));
      } catch (e) {
        console.error('Failed to parse cart from localStorage:', e);
      }
    }
  }, []);

  // Save cart items to local storage whenever they change
  useEffect(() => {
    localStorage.setItem('cart', JSON.stringify(cartItems));
  }, [cartItems]);

  // Log product images on component mount to verify paths
  useEffect(() => {
    console.log('Product data:', product);
    console.log('Product images array:', product.images);
    
    // Debug image paths
    product.images.forEach((image, index) => {
      const processedPath = getValidImagePath(image);
      console.log(`Image ${index + 1} final path:`, processedPath);
    });
  }, [product]);

  // Handle image load error
  const handleImageError = (index: number) => {
    console.error(`Failed to load image at index ${index}:`, product.images[index]);
    setImageError(prev => ({ ...prev, [index]: true }));
  };

  // Add to cart function
  const handleAddToCart = () => {
    const productToAdd = product;
    
    // Check if product already exists in cart
    const existingItemIndex = cartItems.findIndex(item => item.id === product.id.toString());

    if (existingItemIndex >= 0) {
      // If product exists, update quantity
      const updatedCartItems = [...cartItems];
      updatedCartItems[existingItemIndex].quantity += quantity;
      setCartItems(updatedCartItems);
    } else {
      // If product doesn't exist, add new item
      const newCartItem = {
        id: product.id.toString(),
        name: product.name,
        price: product.price,
        quantity: quantity,
        image: getValidImagePath(product.images[0]), // Use first product image
      };

      setCartItems([...cartItems, newCartItem]);
    }

    // Show success toast notification
    toast.success(`${product.name} added to cart!`);
    
    // Reset quantity to 1 after adding to cart
    setQuantity(1);
  };

  // Filter related products from the same category
  const categoryRelatedProducts = products.filter(p => p.category === product?.category);

  return (
    <>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {/* Breadcrumbs */}
        <nav className="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-8">
          {product.breadcrumbs.map((item, index) => (
            <React.Fragment key={index}>
              <Link href="#" className="hover:text-pink-600">
                {item}
              </Link>
              {index < product.breadcrumbs.length - 1 && <ChevronRight className="h-4 w-4" />}
            </React.Fragment>
          ))}
        </nav>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
          {/* Product Images */}
          <div className="space-y-4">
            <div className="aspect-w-1 aspect-h-1 rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800">
              {imageError[selectedImage] ? (
                <div className="w-full h-[600px] flex items-center justify-center text-gray-500">
                  <p>Image not found</p>
                  <p className="text-xs mt-2">Path: {getValidImagePath(product.images[selectedImage])}</p>
                </div>
              ) : (
                <Image
                  src={getValidImagePath(product.images[selectedImage])}
                  alt={product.name}
                  className="w-full h-[600px] object-cover"
                  width={1000}
                  height={1000}
                  onError={() => handleImageError(selectedImage)}
                  priority
                />
              )}
            </div>
            <div className="grid grid-cols-3 gap-4">
              {product.images.map((image, index) => (
                <button
                  key={index}
                  onClick={() => setSelectedImage(index)}
                  className={`rounded-lg overflow-hidden border-2 ${
                    selectedImage === index ? 'border-pink-600' : 'border-transparent'
                  } bg-gray-100 dark:bg-gray-800`}
                >
                  {imageError[index] ? (
                    <div className="w-full h-24 flex items-center justify-center text-xs text-gray-500">
                      No image
                    </div>
                  ) : (
                    <Image 
                      src={getValidImagePath(image)} 
                      alt={`${product.name} thumbnail ${index + 1}`}
                      className="w-full h-24 object-cover"
                      width={200}
                      height={200}
                      onError={() => handleImageError(index)}
                    />
                  )}
                </button>
              ))}
            </div>
          </div>

          {/* Product Info */}
          <div className="space-y-6">
            <span className="inline-block bg-pink-100 dark:bg-pink-900 text-pink-600 dark:text-pink-300 px-3 py-1 rounded-full text-sm font-medium">
              {product.category}
            </span>

            <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
              {product.name}
            </h1>

            <div className="flex items-center space-x-4">
              <div className="flex items-center">
                <Star className="h-5 w-5 text-yellow-400 fill-current" />
                <span className="ml-1 text-sm text-gray-600 dark:text-gray-300">
                  {product.rating} ({product.reviews.length} reviews)
                </span>
              </div>
              <button className="text-gray-400 hover:text-pink-600 transition-colors">
                <Heart className="h-6 w-6" />
              </button>
              <button className="text-gray-400 hover:text-pink-600 transition-colors">
                <Share2 className="h-6 w-6" />
              </button>
            </div>

            <div className="text-3xl font-bold text-gray-900 dark:text-white">
              ${product.price}
            </div>

            <p className="text-gray-600 dark:text-gray-300">
              {product.description}
            </p>

            <div className="space-y-4">
              <h3 className="text-lg font-semibold text-gray-900 dark:text-white">
                Key Benefits
              </h3>
              <ul className="list-disc list-inside space-y-2 text-gray-600 dark:text-gray-300">
                {product.benefits.map((benefit, index) => (
                  <li key={index}>{benefit}</li>
                ))}
              </ul>
            </div>

            <div className="pt-6">
              <div className="flex items-center space-x-6">
                <div className="flex items-center border border-gray-300 rounded-full">
                  <button
                    onClick={() => setQuantity(Math.max(1, quantity - 1))}
                    className="p-2 hover:text-pink-600 transition-colors"
                  >
                    <Minus className="h-5 w-5" />
                  </button>
                  <span className="w-12 text-center">{quantity}</span>
                  <button
                    onClick={() => setQuantity(quantity + 1)}
                    className="p-2 hover:text-pink-600 transition-colors"
                  >
                    <Plus className="h-5 w-5" />
                  </button>
                </div>
                <button 
                  onClick={handleAddToCart}
                  className="flex-1 bg-pink-600 hover:bg-pink-700 text-white px-8 py-3 rounded-full font-medium flex items-center justify-center space-x-2 transition-colors"
                >
                  <ShoppingCart className="h-5 w-5" />
                  <span>Add to Cart</span>
                </button>
              </div>
              
              {/* Cart items count indicator */}
              {cartItems.length > 0 && (
                <div className="mt-4 text-sm text-gray-600 dark:text-gray-300">
                  Cart: {cartItems.reduce((total, item) => total + item.quantity, 0)} item(s)
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Product Details Tabs */}
        <div className="border-b border-gray-200 dark:border-gray-700 mb-8">
          <nav className="flex space-x-8">
            {['description', 'specifications', 'reviews'].map((tab) => (
              <button
                key={tab}
                onClick={() => setActiveTab(tab)}
                className={`py-4 px-1 border-b-2 font-medium text-sm ${
                  activeTab === tab
                    ? 'border-pink-600 text-pink-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                }`}
              >
                {tab.charAt(0).toUpperCase() + tab.slice(1)}
              </button>
            ))}
          </nav>
        </div>

        {/* Tab Content */}
        <div className="mb-16">
          {activeTab === 'description' && (
            <div className="prose dark:prose-invert max-w-none">
              <p className="whitespace-pre-line">{product.longDescription}</p>
            </div>
          )}

          {activeTab === 'specifications' && (
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
              {Object.entries(product.specifications).map(([key, value]) => (
                <div key={key} className="border-b dark:border-gray-700 pb-4">
                  <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">{key}</dt>
                  <dd className="mt-1 text-sm text-gray-900 dark:text-white">{value}</dd>
                </div>
              ))}
            </div>
          )}

          {activeTab === 'reviews' && (
            <div className="space-y-8">
              {product.reviews.map((review) => (
                <div key={review.id} className="border-b dark:border-gray-700 pb-8">
                  <div className="flex items-center justify-between mb-4">
                    <div>
                      <div className="flex items-center">
                        <div className="flex items-center">
                          {[...Array(5)].map((_, i) => (
                            <Star
                              key={i}
                              className={`h-5 w-5 ${
                                i < review.rating
                                  ? 'text-yellow-400 fill-current'
                                  : 'text-gray-300'
                              }`}
                            />
                          ))}
                        </div>
                        <h4 className="ml-2 text-sm font-bold text-gray-900 dark:text-white">
                          {review.title}
                        </h4>
                      </div>
                      <p className="mt-1 text-sm text-gray-500">
                        By {review.user} on {review.date}
                      </p>
                    </div>
                    {review.verified && (
                      <span className="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                        Verified Purchase
                      </span>
                    )}
                  </div>
                  <p className="text-gray-600 dark:text-gray-300">{review.comment}</p>
                  <div className="mt-4 flex items-center space-x-4">
                    <button className="text-sm text-gray-500 hover:text-gray-700">
                      Helpful ({review.helpful})
                    </button>
                    <button className="text-sm text-gray-500 hover:text-gray-700">
                      Report
                    </button>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>

      {/* Related Products Section - Using the new component */}
      <RelatedProducts 
        products={categoryRelatedProducts} 
        currentProductId={product.id} 
      />
      
      {/* Toast Container */}
      <ToastContainer />
    </>
  );
}