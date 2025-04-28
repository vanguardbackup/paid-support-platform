import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { useEffect } from 'react';
import {
    ClockIcon,
    VideoIcon,
    CalendarIcon,
    PhoneIcon,
    MessageSquareIcon,
    AlertCircleIcon,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { Checkbox } from "@/components/ui/checkbox";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/components/ui/accordion";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Support Home',
        href: '/support',
    },
    {
        title: 'Create Request',
        href: '/support/create',
    },
];

interface Category {
    [key: string]: string;
}

interface AssistanceType {
    [key: string]: string;
}

interface Props {
    availableCategories: Category;
    availableAssistanceTypes: AssistanceType;
}

interface SupportRequestForm {
    category: string;
    additional_category_info?: string;
    preferred_assistance_type: string;
    preferred_date: string;
    preferred_time: string;
    timezone: string;
    additional_details: string;
    title: string;
    acceptTerms: boolean;
    [key: string]: string | boolean | undefined;
}

const timezones = [
    { value: 'UTC-12:00', label: '(UTC-12:00) International Date Line West' },
    { value: 'UTC-11:00', label: '(UTC-11:00) Coordinated Universal Time-11' },
    { value: 'UTC-10:00', label: '(UTC-10:00) Hawaii' },
    { value: 'UTC-09:00', label: '(UTC-09:00) Alaska' },
    { value: 'UTC-08:00', label: '(UTC-08:00) Pacific Time (US & Canada)' },
    { value: 'UTC-07:00', label: '(UTC-07:00) Mountain Time (US & Canada)' },
    { value: 'UTC-06:00', label: '(UTC-06:00) Central Time (US & Canada)' },
    { value: 'UTC-05:00', label: '(UTC-05:00) Eastern Time (US & Canada)' },
    { value: 'UTC-04:00', label: '(UTC-04:00) Atlantic Time (Canada)' },
    { value: 'UTC-03:00', label: '(UTC-03:00) Brasilia' },
    { value: 'UTC-02:00', label: '(UTC-02:00) Mid-Atlantic' },
    { value: 'UTC-01:00', label: '(UTC-01:00) Azores' },
    { value: 'UTC+00:00', label: '(UTC+00:00) London, Edinburgh, Dublin' },
    { value: 'UTC+01:00', label: '(UTC+01:00) Berlin, Paris, Madrid' },
    { value: 'UTC+02:00', label: '(UTC+02:00) Athens, Istanbul, Helsinki' },
    { value: 'UTC+03:00', label: '(UTC+03:00) Moscow, St. Petersburg' },
    { value: 'UTC+04:00', label: '(UTC+04:00) Dubai, Abu Dhabi' },
    { value: 'UTC+05:00', label: '(UTC+05:00) Islamabad, Karachi' },
    { value: 'UTC+05:30', label: '(UTC+05:30) Mumbai, New Delhi' },
    { value: 'UTC+06:00', label: '(UTC+06:00) Dhaka' },
    { value: 'UTC+07:00', label: '(UTC+07:00) Bangkok, Jakarta' },
    { value: 'UTC+08:00', label: '(UTC+08:00) Beijing, Singapore, Hong Kong' },
    { value: 'UTC+09:00', label: '(UTC+09:00) Tokyo, Seoul' },
    { value: 'UTC+10:00', label: '(UTC+10:00) Sydney, Melbourne' },
    { value: 'UTC+11:00', label: '(UTC+11:00) Noumea' },
    { value: 'UTC+12:00', label: '(UTC+12:00) Auckland, Wellington' },
];

export default function Create({ availableCategories, availableAssistanceTypes }: Props) {
    const { data, setData, post, processing, errors } = useForm<SupportRequestForm>({
        category: 'technical', // Default selection
        additional_category_info: '',
        preferred_assistance_type: 'email', // Default selection
        preferred_date: '',
        preferred_time: '',
        timezone: '',
        additional_details: '',
        title: '',
        acceptTerms: false
    });

    // Calculate tomorrow's date for the default date value
    useEffect(() => {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const formattedDate = tomorrow.toISOString().split('T')[0];
        setData('preferred_date', formattedDate);
    }, []);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/support');
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Support Request" />

            <div className="my-6">
                <h1 className="text-3xl font-bold">Create Support Request</h1>
                <p className="text-muted-foreground mt-2">
                    Fill out the form below to request support from our team.
                </p>

                <Card className="mt-6 border">
                    <CardHeader>
                        <CardTitle>Support Request Details</CardTitle>
                        <CardDescription>
                            Tell us about your issue, preferred assistance methods, and scheduling preferences.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-6">
                            <Accordion type="multiple" defaultValue={["issue-details", "assistance-preferences", "scheduling-preferences", "additional-info", "terms-agreement"]} className="space-y-4">
                                {/* Issue Details Section */}
                                <AccordionItem value="issue-details" className="border rounded-lg px-4">
                                    <AccordionTrigger className="py-4">
                                        <div className="flex items-center gap-2">
                                            <AlertCircleIcon className="h-5 w-5 text-primary" />
                                            <span className="font-semibold">Issue Details</span>
                                        </div>
                                    </AccordionTrigger>
                                    <AccordionContent className="pb-4 pt-2 space-y-4">
                                        <div className="space-y-2">
                                            <Label htmlFor="title">Issue Title</Label>
                                            <Input
                                                autoFocus
                                                id="title"
                                                value={data.title}
                                                onChange={e => setData('title', e.target.value)}
                                                placeholder="Brief description of your issue"
                                            />
                                            {errors.title && <p className="text-sm text-destructive">{errors.title}</p>}
                                        </div>

                                        <div className="space-y-2">
                                            <Label>Type of Issue</Label>
                                            <RadioGroup
                                                value={data.category}
                                                onValueChange={(value: string) => setData('category', value)}
                                                className="space-y-3"
                                            >
                                                {Object.entries(availableCategories).map(([value, label]) => (
                                                    <div key={value} className="flex items-center space-x-2">
                                                        <RadioGroupItem value={value} id={value} />
                                                        <Label htmlFor={value}>{label}</Label>
                                                    </div>
                                                ))}
                                                {data.category === 'other' && (
                                                    <div className="ml-6 mt-1">
                                                        <Input
                                                            id="additional_category_info"
                                                            value={data.additional_category_info}
                                                            onChange={e => setData('additional_category_info', e.target.value)}
                                                            placeholder="Please specify your issue"
                                                            className="w-full"
                                                        />
                                                    </div>
                                                )}
                                            </RadioGroup>
                                            {errors.category && <p className="text-sm text-destructive">{errors.category}</p>}
                                        </div>
                                    </AccordionContent>
                                </AccordionItem>

                                {/* Assistance Preferences Section */}
                                <AccordionItem value="assistance-preferences" className="border rounded-lg px-4">
                                    <AccordionTrigger className="py-4">
                                        <div className="flex items-center gap-2">
                                            <MessageSquareIcon className="h-5 w-5 text-primary" />
                                            <span className="font-semibold">Assistance Preferences</span>
                                        </div>
                                    </AccordionTrigger>
                                    <AccordionContent className="pb-4 pt-2 space-y-4">
                                        <div className="space-y-2">
                                            <Label>Preferred Method of Assistance</Label>
                                            <RadioGroup
                                                value={data.preferred_assistance_type}
                                                onValueChange={(value: string) => setData('preferred_assistance_type', value)}
                                                className="space-y-3"
                                            >
                                                {Object.entries(availableAssistanceTypes).map(([value, label]) => {
                                                    let icon;
                                                    switch (value) {
                                                        case 'zoom':
                                                        case 'teams':
                                                            icon = <VideoIcon className="h-4 w-4 mr-2 text-muted-foreground" />;
                                                            break;
                                                        case 'phone':
                                                            icon = <PhoneIcon className="h-4 w-4 mr-2 text-muted-foreground" />;
                                                            break;
                                                        case 'chat':
                                                        case 'email':
                                                            icon = <MessageSquareIcon className="h-4 w-4 mr-2 text-muted-foreground" />;
                                                            break;
                                                        default:
                                                            icon = <MessageSquareIcon className="h-4 w-4 mr-2 text-muted-foreground" />;
                                                    }

                                                    return (
                                                        <div key={value} className="flex items-center space-x-2">
                                                            <RadioGroupItem value={value} id={value} />
                                                            {icon}
                                                            <Label htmlFor={value}>{label}</Label>
                                                        </div>
                                                    );
                                                })}
                                            </RadioGroup>
                                            {errors.preferred_assistance_type && <p className="text-sm text-destructive">{errors.preferred_assistance_type}</p>}
                                        </div>
                                    </AccordionContent>
                                </AccordionItem>

                                {/* Scheduling Preferences Section */}
                                <AccordionItem value="scheduling-preferences" className="border rounded-lg px-4">
                                    <AccordionTrigger className="py-4">
                                        <div className="flex items-center gap-2">
                                            <CalendarIcon className="h-5 w-5 text-primary" />
                                            <span className="font-semibold">Scheduling Preferences</span>
                                        </div>
                                    </AccordionTrigger>
                                    <AccordionContent className="pb-4 pt-2 space-y-4">
                                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div className="space-y-2">
                                                <Label htmlFor="preferred_date">Preferred Date</Label>
                                                <Input
                                                    id="preferred_date"
                                                    type="date"
                                                    value={data.preferred_date}
                                                    onChange={e => setData('preferred_date', e.target.value)}
                                                />
                                                {errors.preferred_date && <p className="text-sm text-destructive">{errors.preferred_date}</p>}
                                            </div>

                                            <div className="space-y-2">
                                                <Label htmlFor="preferred_time">Preferred Time</Label>
                                                <Input
                                                    id="preferred_time"
                                                    type="time"
                                                    value={data.preferred_time}
                                                    onChange={e => setData('preferred_time', e.target.value)}
                                                />
                                                {errors.preferred_time && <p className="text-sm text-destructive">{errors.preferred_time}</p>}
                                            </div>

                                            <div className="space-y-2">
                                                <Label htmlFor="timezone">Timezone</Label>
                                                <Select
                                                    value={data.timezone}
                                                    onValueChange={(value: string) => setData('timezone', value)}
                                                >
                                                    <SelectTrigger>
                                                        <SelectValue placeholder="Select timezone" />
                                                    </SelectTrigger>
                                                    <SelectContent>
                                                        {timezones.map((tz) => (
                                                            <SelectItem key={tz.value} value={tz.value}>
                                                                {tz.label}
                                                            </SelectItem>
                                                        ))}
                                                    </SelectContent>
                                                </Select>
                                                {errors.timezone && <p className="text-sm text-destructive">{errors.timezone}</p>}
                                            </div>
                                        </div>

                                        <div className="bg-secondary p-4 rounded-md">
                                            <div className="flex gap-2">
                                                <ClockIcon className="h-5 w-5 text-muted-foreground flex-shrink-0 mt-0.5" />
                                                <div>
                                                    <p className="text-sm font-medium">Support Credit Usage</p>
                                                    <p className="text-sm text-muted-foreground">
                                                        Please note that 1 hour of support time (1 support credit) is the minimum charge,
                                                        regardless of how long the actual session takes.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </AccordionContent>
                                </AccordionItem>

                                {/* Additional Information Section */}
                                <AccordionItem value="additional-info" className="border rounded-lg px-4">
                                    <AccordionTrigger className="py-4">
                                        <div className="flex items-center gap-2">
                                            <MessageSquareIcon className="h-5 w-5 text-primary" />
                                            <span className="font-semibold">Additional Information</span>
                                        </div>
                                    </AccordionTrigger>
                                    <AccordionContent className="pb-4 pt-2 space-y-4">
                                        <div className="space-y-2">
                                            <Label htmlFor="additional_details">Additional Details</Label>
                                            <Textarea
                                                id="additional_details"
                                                value={data.additional_details}
                                                onChange={(e) => setData('additional_details', e.target.value)}
                                                placeholder="Please provide any steps to reproduce the issue, error messages, or other relevant information..."
                                                className="h-32"
                                            />
                                            {errors.additional_details && <p className="text-sm text-destructive">{errors.additional_details}</p>}
                                        </div>
                                    </AccordionContent>
                                </AccordionItem>

                                {/* Terms Agreement Section */}
                                <AccordionItem value="terms-agreement" className="border rounded-lg px-4">
                                    <AccordionTrigger className="py-4">
                                        <div className="flex items-center gap-2">
                                            <AlertCircleIcon className="h-5 w-5 text-primary" />
                                            <span className="font-semibold">Terms Agreement</span>
                                        </div>
                                    </AccordionTrigger>
                                    <AccordionContent className="pb-4 pt-2 space-y-4">
                                        <div className="bg-secondary p-4 rounded-md mb-4">
                                            <p className="text-sm">
                                                By submitting this request, you understand and agree that:
                                            </p>
                                            <ul className="list-disc pl-5 mt-2 space-y-1 text-sm text-muted-foreground">
                                                <li>A minimum of 1 support credit (equivalent to 1 hour of support) will be deducted from your account.</li>
                                                <li>Support credits are non-refundable once used, except in cases where the support session was scheduled incorrectly by our team.</li>
                                                <li>Any additional time beyond 1 hour will be charged in 15-minute increments (0.25 credits).</li>
                                                <li>Cancelled sessions require at least 24 hours notice for credit return.</li>
                                            </ul>
                                        </div>

                                        <div className="flex items-center space-x-2">
                                            <Checkbox
                                                id="acceptTerms"
                                                checked={data.acceptTerms as boolean}
                                                onCheckedChange={(checked) =>
                                                    setData('acceptTerms', checked as boolean)
                                                }
                                            />
                                            <label
                                                htmlFor="acceptTerms"
                                                className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                            >
                                                I understand and agree to the support credit terms
                                            </label>
                                        </div>
                                        {errors.acceptTerms && <p className="text-sm text-destructive mt-1">{errors.acceptTerms}</p>}
                                    </AccordionContent>
                                </AccordionItem>
                            </Accordion>

                            <div className="flex justify-end gap-4 pt-4">
                                <Button
                                    type="submit"
                                    disabled={processing || !data.acceptTerms}
                                >
                                    Submit Request
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
