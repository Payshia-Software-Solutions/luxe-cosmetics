import React, { useState } from "react";
import { FaSort, FaChevronDown, FaChevronUp } from "react-icons/fa";
import { Italiana } from "next/font/google";
import { SortDropdownProps } from "@/types/Sidebar";
// Define custom font styles
const italiana = Italiana({
  weight: "400",
  subsets: ["latin"],
});

const SortDropdown: React.FC<SortDropdownProps> = ({
  onChange,
  currentSort,
}) => {
  const [isOpen, setIsOpen] = useState(false);

  const sortOptions = [
    { value: "price_asc", label: "Price: Low to High" },
    { value: "price_desc", label: "Price: High to Low" },
    { value: "newest", label: "Newest First" },
    { value: "oldest", label: "Oldest First" },
  ];

  const currentSortLabel =
    sortOptions.find((option) => option.value === currentSort)?.label ||
    "Sort Products";

  const handleSelect = (value: string) => {
    onChange(value);
    setIsOpen(false);
  };

  return (
    <div className="relative">
      <button
        onClick={() => setIsOpen(!isOpen)}
        className="flex items-center justify-between w-full px-4 py-3 bg-white dark:bg-[#1e1e1e]  border border-gray-200 dark:border-gray-900 rounded-lg hover:bg-gray-50 transition-colors duration-200"
      >
        <div className="flex items-center">
          <FaSort className="text-gray-400 mr-2" size={14} />
          <span
            className={`${italiana.className} text-gray-700 dark:text-gray-50`}
          >
            {currentSortLabel}
          </span>
        </div>
        <span>
          {isOpen ? <FaChevronUp size={12} /> : <FaChevronDown size={12} />}
        </span>
      </button>

      {isOpen && (
        <div className="absolute z-10 w-full mt-1 bg-white dark:bg-black border border-gray-200 rounded-lg shadow-lg">
          <ul className="py-1">
            {sortOptions.map((option) => (
              <li key={option.value}>
                <button
                  onClick={() => handleSelect(option.value)}
                  className={`block w-full text-left px-4 py-2 text-sm hover:bg-rose-50 ${
                    currentSort === option.value
                      ? "bg-rose-50 text-rose-600 dark:text-gray-50"
                      : "text-gray-700 dark:text-gray-50"
                  }`}
                >
                  {option.label}
                </button>
              </li>
            ))}
          </ul>
        </div>
      )}
    </div>
  );
};

export default SortDropdown;
