import React from "react";

interface TopBarProps {
  showTopBar: boolean;
  message: string;
}

const TopBar: React.FC<TopBarProps> = ({ showTopBar, message }) => {
  return (
    <div
      className={`bg-[#8c8181] dark:bg-[#161313] text-white text-center py-2 text-sm transition-all duration-300 ease-in-out transform z-50 ${
        showTopBar ? "translate-y-0 opacity-100" : "-translate-y-full opacity-0"
      }`}
      style={{
        position: showTopBar ? "static" : "absolute",
        zIndex: 40,
        width: "100%",
      }}
    >
      <p>{message}</p>
    </div>
  );
};

export default TopBar;
