import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type SharedData, type Transaction } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Checkbox } from "@/components/ui/checkbox";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { CoinsIcon } from "lucide-react";
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/components/ui/accordion";

interface PurchaseCreditsPageProps extends SharedData {
    transactions: Transaction[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Support Home',
        href: '/support',
    },
    {
        title: 'Purchase Credits',
        href: '/support/credits/purchase',
    },
];

// Mock transaction data - in a real app, this would come from the server
const mockTransactions = [
    {
        id: "tx_123456789",
        date: "2025-04-15",
        amount: 3,
        cost: 90.00,
        status: "Completed",
        invoice_url: "#"
    },
    {
        id: "tx_987654321",
        date: "2025-03-20",
        amount: 5,
        cost: 150.00,
        status: "Completed",
        invoice_url: "#"
    },
    {
        id: "tx_567891234",
        date: "2025-02-10",
        amount: 1,
        cost: 30.00,
        status: "Completed",
        invoice_url: "#"
    }
];

// FAQ data
const faqItems = [
    {
        question: "What are support credits?",
        answer: "Support credits are used to access premium support services. Each credit can be redeemed for a single support session or consultation with our expert team."
    },
    {
        question: "How much do credits cost?",
        answer: "Each support credit costs ₤30. You can purchase multiple credits at once and use them whenever you need assistance."
    },
    {
        question: "Do credits expire?",
        answer: "No, support credits do not expire. Once purchased, they remain in your account until you use them."
    },
    {
        question: "Can I get a refund for unused credits?",
        answer: "We do not offer refunds for purchased credits. However, they never expire, so you can use them at any time."
    },
    {
        question: "How do I use my credits?",
        answer: "You can use your credits by booking a support session through our platform. The system will automatically deduct the required number of credits from your account balance once youve been helped."
    },
    {
        question: "What if I have insufficient credits?",
        answer: "If you do not have enough credits on your account, you will be prompted to purchase more to cover the outstanding balance."
    },
    {
        question: "Who do you use for payment processing?",
        answer: "We use Mollie for secure payment processing. Your payment information is handled securely and never stored on our servers."
    }
];

export default function PurchaseCredits() {
    const page = usePage<PurchaseCreditsPageProps>();
    const { auth } = page.props;
    const creditPrice = 30; // $30 per credit
    const [creditAmount, setCreditAmount] = useState(1);
    const [totalPrice, setTotalPrice] = useState(creditPrice);

    // Update total price when credit amount changes
    useEffect(() => {
        setTotalPrice(creditAmount * creditPrice);
    }, [creditAmount]);

    const { data, post, processing} = useForm({
        credit_amount: creditAmount,
        agree_to_terms: false,
    });

    const handleSubmit = (e: { preventDefault: () => void; }) => {
        e.preventDefault();
        // In a real app, this would submit to the server
        alert(`Purchasing ${creditAmount} credits for $${totalPrice}`);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Purchase Credits" />
            <div className="my-6">
                <h1 className="text-3xl font-bold">Purchase Support Credits</h1>
                <p className="text-muted-foreground mt-2">
                    Support credits are used to access paid support. Each credit can be redeemed for a single support session or consultation with our expert team.
                </p>

                <div className="flex flex-col md:flex-row gap-6 mt-6">
                    {/* Left Column - Purchase Form */}
                    <Card className="md:w-2/3">
                        <CardContent className="p-6">
                            <div className="flex items-center gap-2 mb-6">
                                <div className="bg-pink-100 dark:bg-pink-950 p-3 rounded-full">
                                    <CoinsIcon size={24} className="text-pink-600 dark:text-pink-400" />
                                </div>
                                <div>
                                    <h2 className="text-2xl font-bold">Support Credits</h2>
                                    <p className="text-muted-foreground">Current balance: {auth.user.credit_balance} credits</p>
                                </div>
                            </div>

                            <div className="space-y-6">
                                <div>
                                    <Label htmlFor="credit-amount">Number of Credits</Label>
                                    <div className="flex items-center gap-4 mt-2">
                                        <Button
                                            type="button"
                                            variant="outline"
                                            size="sm"
                                            onClick={() => setCreditAmount(Math.max(1, creditAmount - 1))}
                                            disabled={creditAmount <= 1}
                                        >
                                            -
                                        </Button>
                                        <Input
                                            id="credit-amount"
                                            type="number"
                                            min="1"
                                            value={creditAmount}
                                            onChange={(e) => setCreditAmount(parseInt(e.target.value) || 1)}
                                            className="w-20 text-center"
                                        />
                                        <Button
                                            type="button"
                                            variant="outline"
                                            size="sm"
                                            onClick={() => setCreditAmount(creditAmount + 1)}
                                        >
                                            +
                                        </Button>
                                    </div>
                                </div>

                                <div className="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                    <div className="flex justify-between mb-2">
                                        <span>Price per credit:</span>
                                        <span>₤{creditPrice.toFixed(2)}</span>
                                    </div>
                                    <div className="flex justify-between mb-2">
                                        <span>Quantity:</span>
                                        <span>{creditAmount}</span>
                                    </div>
                                    <div className="border-t pt-2 mt-2 flex justify-between font-bold">
                                        <span>Total amount:</span>
                                        <span>₤{totalPrice.toFixed(2)}</span>
                                    </div>
                                </div>

                                <Button
                                    type="button"
                                    className="w-full flex items-center justify-center gap-2"
                                    onClick={() => {
                                        // Redirect to Mollie's payment page
                                        window.location.href = `/mollie/checkout?credits=${creditAmount}`;
                                    }}
                                >
                                    <CoinsIcon size={16} />
                                    Proceed to Payment
                                </Button>
                            </div>
                        </CardContent>
                    </Card>

                    {/* Right Column - FAQ */}
                    <Card className="md:w-1/3">
                        <CardContent className="p-6">
                            <h2 className="text-xl font-semibold mb-4">Frequently Asked Questions</h2>
                            <Accordion type="single" collapsible className="w-full">
                                {faqItems.map((item, index) => (
                                    <AccordionItem key={index} value={`item-${index}`}>
                                        <AccordionTrigger className="text-left">
                                            {item.question}
                                        </AccordionTrigger>
                                        <AccordionContent>
                                            {item.answer}
                                        </AccordionContent>
                                    </AccordionItem>
                                ))}
                            </Accordion>
                        </CardContent>
                    </Card>
                </div>

                {/* Transaction History */}
                <div className="mt-6">
                    <h2 className="text-2xl font-bold mb-4">Transaction History</h2>
                    <Card>
                        <div className="overflow-x-auto">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Transaction ID</TableHead>
                                        <TableHead>Date</TableHead>
                                        <TableHead>Credits</TableHead>
                                        <TableHead>Amount</TableHead>
                                        <TableHead>Status</TableHead>
                                        <TableHead>Invoice</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {mockTransactions.length > 0 ? (
                                        mockTransactions.map((transaction) => (
                                            <TableRow key={transaction.id}>
                                                <TableCell className="font-medium">#{transaction.id.substring(0, 8)}</TableCell>
                                                <TableCell>{transaction.date}</TableCell>
                                                <TableCell>{transaction.amount}</TableCell>
                                                <TableCell>${transaction.cost.toFixed(2)}</TableCell>
                                                <TableCell>
                                                    <Badge variant="open">
                                                        {transaction.status}
                                                    </Badge>
                                                </TableCell>
                                                <TableCell>
                                                    <a href={transaction.invoice_url} className="inline-flex items-center gap-1 text-blue-600 hover:underline">
                                                        PDF
                                                    </a>
                                                </TableCell>
                                            </TableRow>
                                        ))
                                    ) : (
                                        <TableRow>
                                            <TableCell colSpan={6} className="text-center py-6">
                                                No transactions found
                                            </TableCell>
                                        </TableRow>
                                    )}
                                </TableBody>
                            </Table>
                        </div>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
