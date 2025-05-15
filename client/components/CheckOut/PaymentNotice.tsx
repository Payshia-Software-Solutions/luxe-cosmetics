"use client";
import React from "react";


// Define interface for payment details
// interface PaymentDetails {
//   order_id: string;
//   items: string;
//   currency: string;
//   amount: string;
//   first_name: string;
//   last_name: string;
//   email: string;
//   phone: string;
//   address: string;
//   city: string;
//   country: string;
//   return_url: string;
//   cancel_url: string;
//   notify_url: string;
// }

const PaymentNotice: React.FC = () => {
//   const handlePayment = async (): Promise<void> => {
//     const paymentDetails: PaymentDetails = {
//       order_id: "12345",
//       items: "Test Item",
//       currency: "LKR",
//       amount: "1000",
//       first_name: "John",
//       last_name: "Doe",
//       email: "john.doe@example.com",
//       phone: "0771234567",
//       address: "No.1, Main Street",
//       city: "Colombo",
//       country: "Sri Lanka",
//       return_url: "https://kduserver.payshia.com/payment/return",
//       cancel_url: "https://kduserver.payshia.com/payment/cancel",
//       notify_url: "https://kduserver.payshia.com/payment/notify",
//     };

//     try {
//       const { data } = await axios.post<string>(
//         "http://localhost/TeaJarWebsite/server/payment/initiate-payment",
//         paymentDetails,
//         {
//           headers: { "Content-Type": "application/x-www-form-urlencoded" },
//         }
//       );

//       // Assuming the server returns an HTML form
//       document.open(); // Clear the current document
//       document.write(data); // Write the new content (e.g., auto-submitting form)
//       document.close(); // Complete the document loading
//     } catch (error) {
//       console.error("Payment initiation failed:", error);
//     }
//   };

  return (
    <div className="mx-auto p-6 bg-white">
      <div className="p-4 bg-yellow-50 border border-yellow-200 rounded-md">
        <div className="flex items-start">
          <div className="ml-3">
            <h3 className="text-sm font-semibold text-yellow-800">
              Important Notice
            </h3>
            <p className="mt-2 text-sm text-yellow-700">
              Please do not close your tab or browser after completing your
              payment. Kindly remain on this tab/browser until you are
              redirected to the order confirmation page to ensure that your
              transaction is processed successfully.
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default PaymentNotice;