"use client";
import { useState, useEffect } from "react";

// Define interface for component props
interface PaymentOptionsProps {
  setSelectedPaymentMethod: (method: string) => void;
}

// Define type for payment methods
type PaymentMethod = "card" | "cod";

const PaymentOptions: React.FC<PaymentOptionsProps> = ({
  setSelectedPaymentMethod,
}) => {
  const [selectedMethod, setSelectedMethod] = useState<PaymentMethod>("card");

  // Update the parent state when the selected method changes
  useEffect(() => {
    setSelectedPaymentMethod(selectedMethod);
  }, [selectedMethod, setSelectedPaymentMethod]);

  return (
    <div className="mx-auto p-6 bg-white">
      <h2 className="text-lg font-semibold mb-2">Payment</h2>
      <p className="text-sm text-gray-600 mb-4">
        All transactions are secure and encrypted.
      </p>

      <div className="space-y-4">
        {/* Card Payment Option */}
        <div
          className={`border rounded-md ${
            selectedMethod === "card" ? "border-indigo-500" : "border-gray-300"
          }`}
        >
          <label
            className="flex items-center p-4 cursor-pointer"
            onClick={() => setSelectedMethod("card")}
          >
            <input
              type="radio"
              name="paymentMethod"
              value="card"
              checked={selectedMethod === "card"}
              onChange={() => setSelectedMethod("card")}
              className="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
            />
            <span className="ml-2 flex-1 text-gray-700">
              Bank Card / Bank Account - PayHere
            </span>
            <div className="flex space-x-2">
              {/* <img src="/visa.png" alt="Visa" className="w-6 h-6" />
              <img src="/mastercard.png" alt="MasterCard" className="w-6 h-6" />
              <img src="/amex.png" alt="Amex" className="w-6 h-6" />
              <img src="/discover.png" alt="Discover" className="w-6 h-6" /> */}
            </div>
          </label>
          {selectedMethod === "card" && (
            <div className="p-4 bg-gray-50 border-t border-gray-300">
              <div className="flex items-center justify-center">
                <div className="w-16 h-16 border rounded-md flex items-center justify-center">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    className="h-8 w-8 text-gray-500"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      strokeWidth="2"
                      d="M4 4h16v16H4zM8 4v16m8-16v16"
                    />
                  </svg>
                </div>
              </div>
              <p className="text-center text-sm text-gray-600 mt-2">
                After clicking &quot;Pay now&quot;, you will be redirected to
                Bank Card / Bank Account - PayHere to complete your purchase
                securely.
              </p>
            </div>
          )}
        </div>

        {/* Mintpay Option */}
        {/* <div
          className={`border rounded-md ${
            selectedMethod === "mintpay"
              ? "border-indigo-500"
              : "border-gray-300"
          }`}
        >
          <label
            className="flex items-center p-4 cursor-pointer"
            onClick={() => setSelectedMethod("mintpay")}
          >
            <input
              type="radio"
              name="paymentMethod"
              value="mintpay"
              checked={selectedMethod === "mintpay"}
              onChange={() => setSelectedMethod("mintpay")}
              className="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
            />
            <span className="ml-2 flex-1 text-gray-700">
              Mintpay | Shop now. Pay later.
            </span>
            <img src="/visa.png" alt="Visa" className="w-6 h-6" />
          </label>
        </div> */}

        {/* Koko Option */}
        {/* <div
          className={`border rounded-md ${
            selectedMethod === "koko" ? "border-indigo-500" : "border-gray-300"
          }`}
        >
          <label
            className="flex items-center p-4 cursor-pointer"
            onClick={() => setSelectedMethod("koko")}
          >
            <input
              type="radio"
              name="paymentMethod"
              value="koko"
              checked={selectedMethod === "koko"}
              onChange={() => setSelectedMethod("koko")}
              className="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
            />
            <span className="ml-2 flex-1 text-gray-700">
              Koko: Buy Now Pay Later
            </span>
            <img src="/visa.png" alt="Visa" className="w-6 h-6" />
          </label>
        </div> */}

        {/* Cash on Delivery Option */}
        <div
          className={`border rounded-md ${
            selectedMethod === "cod" ? "border-indigo-500" : "border-gray-300"
          }`}
        >
          <label
            className="flex items-center p-4 cursor-pointer"
            onClick={() => setSelectedMethod("cod")}
          >
            <input
              type="radio"
              name="paymentMethod"
              value="cod"
              checked={selectedMethod === "cod"}
              onChange={() => setSelectedMethod("cod")}
              className="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
            />
            <span className="ml-2 flex-1 text-gray-700">Cash On Delivery</span>
          </label>
        </div>
      </div>
    </div>
  );
};

export default PaymentOptions;
