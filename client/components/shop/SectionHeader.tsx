import React from "react";
import { FaChevronDown, FaChevronUp } from "react-icons/fa";
import { Italiana } from "next/font/google";
import {SectionHeaderProps} from "@/types/shop";

// Define custom font styles
const italiana = Italiana({
  weight: "400",
  subsets: ["latin"],
});



const SectionHeader: React.FC<SectionHeaderProps> = ({ title, isExpanded, count, onToggle }) => {
  return (
    <button onClick={onToggle} className="flex justify-between items-center w-full py-3 group transition-colors">
      <div className="flex items-center gap-2">
        <div className={italiana.className}>
          <h3 className="text-xl font-bold group-hover:text-rose-500 transition-colors">{title}</h3>
        </div>
        {count !== undefined && (
          <span className="text-xs bg-rose-100 text-rose-600 px-2 py-0.5 rounded-full">
            {count}
          </span>
        )}
      </div>
      <span className="w-6 h-6 flex items-center justify-center rounded-full text-gray-500 bg-gray-100 group-hover:bg-rose-100 group-hover:text-rose-500 transition-all">
        {isExpanded ? <FaChevronUp size={12} /> : <FaChevronDown size={12} />}
      </span>
    </button>
  );
};

export default SectionHeader;