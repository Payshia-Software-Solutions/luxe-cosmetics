"use client";

import React from "react";

interface TitleHeaderProps {
  title: string;
  description: string;
}

const TitleHeader: React.FC<TitleHeaderProps> = ({ title, description }) => {
  return (
    <div className="text-center ">
      <div className="inline-block">
        <h2 className="text-4xl md:text-6xl lg:text-7xl font-bold bg-gradient-to-r from-rose-600 via-orange-500 to-amber-500 bg-clip-text text-transparent mb-4">
          {title}
        </h2>
        <div className="h-1 w-32 bg-gradient-to-r from-rose-500 to-amber-500 mx-auto rounded-full" />
      </div>
      <p className="text-lg text-gray-600 dark:text-gray-300 mt-6 max-w-2xl mx-auto">
        {description}
      </p>
    </div>
  );
};

export default TitleHeader;
