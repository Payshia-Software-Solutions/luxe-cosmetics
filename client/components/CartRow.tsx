import React from 'react';
import Image from 'next/image';
import { Trash2, Plus, Minus } from 'lucide-react';
import { CartRowProps } from '@/types/Sidebar'; // Adjust the import path as necessary

const CartRow: React.FC<CartRowProps> = ({ item, onQuantityChange, onRemove }) => {
  // Fallback image URL - replace with your actual fallback image
  const fallbackImage = '/images/placeholder.jpg';

  return (
    <div className="flex space-x-4">
      {/* Product Image */}
      <div className="flex-shrink-0 w-20 h-20 relative rounded-md overflow-hidden">
        <Image
          src={item.image || fallbackImage}
          alt={item.name}
          layout="fill"
          objectFit="cover"
          className="rounded-md"
        />
      </div>

      {/* Product Details */}
      <div className="flex-1 flex flex-col">
        <div className="flex justify-between">
          <h3 className="text-sm font-medium text-gray-900 dark:text-white">{item.name}</h3>
          <p className="text-sm font-medium text-gray-900 dark:text-white">
            ${item.price.toFixed(2)}
          </p>
        </div>
        <div className="flex items-center justify-between mt-2">
          {/* Quantity Controls */}
          <div className="flex items-center border border-gray-200 dark:border-gray-700 rounded-full">
            <button
              onClick={() => onQuantityChange(item.id, -1)}
              className="p-1 rounded-l-full hover:bg-gray-100 dark:hover:bg-gray-800"
              aria-label="Decrease quantity"
            >
              <Minus className="h-4 w-4 text-gray-500 dark:text-gray-400" />
            </button>
            <span className="px-2 text-sm text-gray-700 dark:text-gray-300">{item.quantity}</span>
            <button
              onClick={() => onQuantityChange(item.id, 1)}
              className="p-1 rounded-r-full hover:bg-gray-100 dark:hover:bg-gray-800"
              aria-label="Increase quantity"
            >
              <Plus className="h-4 w-4 text-gray-500 dark:text-gray-400" />
            </button>
          </div>

          {/* Remove Button */}
          <button
            onClick={() => onRemove(item.id)}
            className="text-red-500 hover:text-red-600 dark:hover:text-red-400"
            aria-label="Remove item"
          >
            <Trash2 className="h-4 w-4" />
          </button>
        </div>
      </div>
    </div>
  );
};

export default CartRow;