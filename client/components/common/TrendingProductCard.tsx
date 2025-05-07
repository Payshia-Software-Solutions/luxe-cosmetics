import React, { useState } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { Star, Heart,  Eye, Clock, Flame } from 'lucide-react';


// Assuming we're using the Product interface from your previous code
interface Product {
  id: string;
  slug: string;
  name: string;
  price: number;
  rating: number;
  review: number;
  description: string;
  images: string[];
  category: string;
  // other properties...
}

interface TrendingProductCardProps {
  product: Product;
  onToggleWishlist?: (productId: string) => void;
  isInWishlist?: boolean;
  salesCount?: number;
}

const TrendingProductCard: React.FC<TrendingProductCardProps> = ({ 
  product, 
  onToggleWishlist,
  isInWishlist = false,
  salesCount = Math.floor(Math.random() * 1000) + 1
}) => {
  const [isHovering, setIsHovering] = useState(false);
  const hasMultipleImages = product.images.length > 1;
  
  // Calculate discount percentage (just for demo)
  const discountPercent = 15;
  const originalPrice = product.price * (100 / (100 - discountPercent));
  
  return (
    <div 
      className="mb-12 group relative rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl"
      onMouseEnter={() => setIsHovering(true)}
      onMouseLeave={() => setIsHovering(false)}
    >
      {/* Trending indicator ribbon */}
      <div className="absolute left-0 top-4 z-10">
        <div className="bg-gradient-to-r from-pink-600 to-purple-600 text-white py-1 px-4 rounded-r-full shadow-md flex items-center gap-1">
          <Flame className="h-4 w-4" />
          <span className="font-medium text-sm">Trending</span>
        </div>
      </div>
      
      {/* Image container */}
      <div className="aspect-w-1 aspect-h-1 relative overflow-hidden">
        {/* Main image */}
        <Image
          src={product.images[0]}
          alt={product.name}
          className={`w-full h-80 object-cover transform transition-all duration-500 ${
            isHovering && hasMultipleImages ? 'opacity-0' : 'opacity-100'
          } group-hover:scale-105`}
          width={1000}
          height={1000}
          priority
        />
        
        {/* Second image on hover */}
        {hasMultipleImages && (
          <Image
            src={product.images[1]}
            alt={`${product.name} - alternate view`}
            className={`absolute inset-0 w-full h-80 object-cover transform transition-opacity duration-500 ${
              isHovering ? 'opacity-100 scale-105' : 'opacity-0 scale-100'
            }`}
            width={1000}
            height={1000}
          />
        )}
        
        {/* Sales counter badge */}
        <div className="absolute top-4 right-4 bg-pink-600 text-white px-3 py-1 rounded-full text-sm font-medium shadow-md flex items-center gap-1">
          <Flame className="h-4 w-4" />
          <span>{salesCount} sold</span>
        </div>
        
        {/* Limited time indicator */}
        <div className="absolute bottom-4 left-4 bg-black/70 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-medium shadow-md flex items-center gap-1">
          <Clock className="h-4 w-4" />
          <span>Limited time</span>
        </div>
        
        {/* Wishlist button */}
        {onToggleWishlist && (
          <button 
            onClick={(e) => {
              e.stopPropagation();
              onToggleWishlist(product.id);
            }}
            className="absolute top-14 right-4 p-2 rounded-full bg-white/80 backdrop-blur-sm shadow-sm transition-all hover:bg-white hover:scale-110"
            aria-label={isInWishlist ? "Remove from wishlist" : "Add to wishlist"}
          >
            <Heart 
              className={`h-5 w-5 ${isInWishlist ? 'text-pink-600 fill-pink-600' : 'text-gray-700'}`} 
            />
          </button>
        )}
        
        {/* Image indicator dots */}
        {hasMultipleImages && (
          <div className="absolute bottom-4 right-4 flex gap-1">
            {product.images.slice(0, 2).map((_, index) => (
              <span 
                key={index}
                className={`h-2 w-2 rounded-full transition-all ${
                  (index === 0 && !isHovering) || (index === 1 && isHovering)
                    ? 'bg-white scale-110' 
                    : 'bg-white/50'
                }`}
              />
            ))}
          </div>
        )}
      </div>
      
      {/* Content area */}
      <div className="p-6 bg-white dark:bg-[#1c3c34]">
        {/* Category and Name */}
        <div className="mb-2">
          <p className="text-sm text-pink-600 dark:text-pink-400 font-medium mb-1">
            {product.category}
          </p>
          <h3 className="text-xl font-semibold text-gray-900 dark:text-white line-clamp-1">
            {product.name}
          </h3>
        </div>
        
        {/* Rating and Review Count */}
        <div className="flex items-center mb-3">
          <div className="flex">
            {[...Array(5)].map((_, i) => (
              <Star 
                key={i} 
                className={`h-5 w-5 ${
                  i < Math.floor(product.rating) 
                    ? 'text-yellow-400 fill-current' 
                    : 'text-gray-300 dark:text-gray-600'
                }`} 
              />
            ))}
          </div>
          <span className="ml-2 text-sm text-gray-600 dark:text-gray-300">
            ({product.review})
          </span>
        </div>
        
        {/* Short description */}
        <p className="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
          {product.description}
        </p>
        
        {/* Price and Actions */}
        <div className="flex items-center justify-between">
          <div className="flex flex-col">
            <span className="text-2xl font-bold text-gray-900 dark:text-white">
              {new Intl.NumberFormat('en-LK', { style: 'currency', currency: 'LKR' }).format(product.price)}
            </span>
            <div className="flex items-center gap-2">
              <span className="text-sm text-gray-500 dark:text-gray-400 line-through">
                {new Intl.NumberFormat('en-LK', { style: 'currency', currency: 'LKR' }).format(originalPrice)}
              </span>
              <span className="text-sm font-medium text-green-600 dark:text-green-400">
                {discountPercent}% OFF
              </span>
            </div>
          </div>

          <div className="flex gap-2">
            <Link href={`/products/${product.slug}`}>
              <button className="bg-pink-600 hover:bg-pink-700 text-white px-5 py-2 rounded-full text-sm font-medium transition-colors flex items-center gap-1">
                <Eye className="h-4 w-4" />
                <span>View</span>
              </button>
            </Link>
          </div>
        </div>
      </div>
      
      {/* Animation pulse effect for trending products */}
      <div className="absolute inset-0 bg-gradient-to-r from-pink-500/30 to-purple-500/30 rounded-2xl group-hover:opacity-0 opacity-0 group-hover:animate-pulse pointer-events-none" />
    </div>
  );
};

export default TrendingProductCard;