import React, { useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { Star, Heart, ShoppingBag } from 'lucide-react';

// Using your exact Product interface
export interface Product {
  id: string;
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

interface ProductCardProps {
  product: Product;
  onAddToCart: (productId: string) => void;
  onToggleWishlist: (productId: string) => void;
  isInWishlist: boolean;
}

const ProductCard: React.FC<ProductCardProps> = ({ 
  product, 
  onAddToCart, 
  onToggleWishlist, 
  isInWishlist 
}) => {
  const { specifications } = product;
  const skinType = specifications['Skin Type'] || 'All Skin Types';
  const [isHovering, setIsHovering] = useState(false);
  
  // Only show hover image if there are at least 2 images
  const hasHoverImage = product.images.length >= 2;
  
  return (
    <div 
      className="group h-full"
      onMouseEnter={() => setIsHovering(true)}
      onMouseLeave={() => setIsHovering(false)}
    >
      <Link href={`/products/${product.slug}`} className="h-full block">
        <div className="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl h-full flex flex-col">
          {/* Fixed height image container */}
          <div className="relative w-full h-64">
            {/* Main image */}
            <Image
              src={product.images[0]}
              alt={product.name}
              className={`absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ${
                isHovering && hasHoverImage ? 'opacity-0' : 'opacity-100'
              }`}
              width={500}
              height={500}
              priority
            />
            
            {/* Hover image (second image) */}
            {hasHoverImage && (
              <Image
                src={product.images[1]}
                alt={`${product.name} - alternate view`}
                className={`absolute inset-0 w-full h-full object-cover transition-opacity duration-300 ${
                  isHovering ? 'opacity-100' : 'opacity-0'
                }`}
                width={500}
                height={500}
              />
            )}
            
            {/* Category badge */}
            <div className="absolute top-2 left-2">
              <span className="px-2 py-1 text-xs font-bold uppercase rounded bg-pink-100 text-pink-700 dark:bg-pink-900 dark:text-pink-200">
                {product.category}
              </span>
            </div>
            
            {/* Wishlist button */}
            <button 
              onClick={(e) => {
                e.preventDefault();
                onToggleWishlist(product.id);
              }}
              className="absolute top-2 right-2 p-2 rounded-full bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-sm transition-all hover:scale-110"
              aria-label={isInWishlist ? "Remove from wishlist" : "Add to wishlist"}
            >
              <Heart 
                className={`h-5 w-5 ${isInWishlist ? 'text-pink-600 fill-pink-600' : 'text-gray-600 dark:text-gray-300'}`} 
              />
            </button>
            
            {/* Image indicator dots */}
            {product.images.length > 1 && (
              <div className="absolute bottom-2 left-0 right-0 flex justify-center gap-1">
                {product.images.slice(0, 2).map((_, index) => (
                  <span 
                    key={index}
                    className={`h-2 w-2 rounded-full ${
                      (index === 0 && !isHovering) || (index === 1 && isHovering)
                        ? 'bg-white' 
                        : 'bg-white/50'
                    }`}
                  />
                ))}
              </div>
            )}
          </div>
          
          {/* Content area with fixed heights */}
          <div className="p-4 flex flex-col flex-grow">
            {/* Brand area - fixed height */}
            <div className="h-6">
              {product.slug.includes('cerave') && (
                <p className="text-sm font-medium text-pink-600 dark:text-pink-400 uppercase">CeraVe</p>
              )}
              {product.slug.includes('loreal') && (
                <p className="text-sm font-medium text-pink-600 dark:text-pink-400 uppercase">L'Oréal</p>
              )}
              {product.slug.includes('garnier') && (
                <p className="text-sm font-medium text-pink-600 dark:text-pink-400 uppercase">Garnier</p>
              )}
            </div>
            
            {/* Product name - fixed height */}
            <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2 line-clamp-1 h-7">
              {product.name}
            </h3>
            
            {/* Rating - fixed height */}
            <div className="flex items-center mb-2 h-5">
              <Star className="h-4 w-4 text-yellow-400 fill-current" />
              <span className="ml-1 text-sm text-gray-600 dark:text-gray-300">
                {product.rating} ({product.review})
              </span>
            </div>
            
            {/* Badges - fixed height */}
            <div className="flex flex-wrap gap-1 mb-2 h-6 overflow-hidden">
              <span className="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full">
                {skinType}
              </span>
              
              {product.benefits && product.benefits.length > 0 && (
                <span className="px-2 py-1 text-xs bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full">
                  {product.benefits[0]}
                </span>
              )}
            </div>
            
            {/* Description - fixed height with line clamp */}
            <p className="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2 h-10">
              {product.description}
            </p>
            
            {/* Push the price and button to the bottom */}
            <div className="mt-auto">
              <div className="flex items-center justify-between">
                <span className="text-xl font-bold text-gray-900 dark:text-white">
                  ${product.price.toFixed(2)}
                </span>
                
                <button 
                  onClick={(e) => {
                    e.preventDefault();
                    onAddToCart(product.id);
                  }}
                  className="flex items-center gap-1 bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors"
                >
                  <ShoppingBag className="h-4 w-4" />
                  <span>Add to Cart</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </Link>
    </div>
  );
};

export default ProductCard;