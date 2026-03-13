Accident Report Assignment

Hello {{ $coordinator->name }},

You have been assigned as the coordinator for the following Accident report:

Accident ID: #{{ str_pad($incident->id, 6, '0', STR_PAD_LEFT) }}
Brief Description: {{ $incident->brief_description }}
Date of Occurrence: {{ $incident->date_of_occurrence?->format('d/m/Y') ?? 'Not specified' }}
Location: {{ $incident->location ?? 'Not specified' }}
Status: {{ ucfirst($incident->status) }}

Please review the Accident details and begin your investigation.

View Accident Report: {{ route('incidents.show', $incident->id) }}

---
This is an automated notification. Please do not reply to this email.
