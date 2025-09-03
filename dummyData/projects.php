<?php
// Dummy data for demonstration
$usertype = 'client';

$projects = [
  [
    'id' => 1,
    'name' => 'Website Redesign',
    'client' => 'Acme Corp',
    'start_date' => '2024-05-01',
    'due_date' => '2024-07-15',
    'status' => 'ongoing',
    'end_date' => '',
    'description' => 'Redesigning the corporate website for Acme Corp.'
  ],
  [
    'id' => 2,
    'name' => 'Mobile App Development',
    'client' => 'Beta Ltd',
    'start_date' => '2024-06-10',
    'due_date' => '2024-09-01',
    'status' => 'ongoing',
    'end_date' => '',
    'description' => 'Developing a cross-platform mobile app.'
  ],
  [
    'id' => 3,
    'name' => 'SEO Optimization #Test',
    'client' => 'Gamma Inc',
    'start_date' => '2024-02-01',
    'due_date' => '2024-04-15',
    'status' => 'finished',
    'end_date' => '2024-04-20',
    'description' => 'Improved SEO for Gamma Inc\'s e-commerce site.'
  ],
  [
    'id' => 4,
    'name' => 'Graphic Design Project',
    'client' => 'Gamma Inc',
    'start_date' => '2024-04-01',
    'due_date' => '2024-05-15',
    'status' => 'ongoing',
    'end_date' => '',
    'description' => 'Creating marketing materials for Gamma Inc.'
  ],
  [
    'id' => 5,
    'name' => 'Video Production',
    'client' => 'Delta Studios',
    'start_date' => '2024-06-01',
    'due_date' => '2024-08-15',
    'status' => 'finished',
    'end_date' => '2024-08-20',
    'description' => 'Producing a promotional video for Delta Studios.'
  ],
  [
    'id' => 6,
    'name' => 'Social Media Campaign',
    'client' => 'Epsilon Agency',
    'start_date' => '2024-05-01',
    'due_date' => '2024-09-15',
    'status' => 'ongoing',
    'end_date' => '',
    'description' => 'Managing a social media campaign for Epsilon Agency.'
  ],
  [
    'id' => 7,
    'name' => 'Content Writing',
    'client' => 'Zeta Publishing',
    'start_date' => '2024-03-01',
    'due_date' => '2024-06-15',
    'status' => 'finished',
    'end_date' => '2024-06-20',
    'description' => 'Writing articles and blog posts for Zeta Publishing.'
  ],
  [
    'id' => 8,
    'name' => 'Data Analysis Project',
    'client' => 'Eta Analytics',
    'start_date' => '2024-07-01',
    'due_date' => '2024-10-15',
    'status' => 'ongoing',
    'end_date' => '',
    'description' => 'Analyzing data for Eta Analytics\' new product launch.'
  ],
  [
    'id' => 9,
    'name' => 'UI/UX Design',
    'client' => 'Theta Tech',
    'start_date' => '2024-08-01',
    'due_date' => '2024-11-15',
    'status' => 'finished',
    'end_date' => '2024-11-20',
    'description' => 'Designing the user interface for Theta Tech\'s new app.'
  ],
  [
    'id' => 10,
    'name' => 'Cloud Migration',
    'client' => 'Iota Solutions',
    'start_date' => '2024-09-01',
    'due_date' => '2024-12-15',
    'status' => 'ongoing',
    'end_date' => '',
    'description' => 'Migrating Iota Solutions\' infrastructure to the cloud.'
  ],
  [
    'id' => 11,
    'name' => 'Cybersecurity Audit',
    'client' => 'Kappa Security',
    'start_date' => '2024-10-01',
    'due_date' => '2025-01-15',
    'status' => 'finished',
    'end_date' => '2025-01-20',
    'description' => 'Conducting a cybersecurity audit for Kappa Security.'
  ],
  [
    'id' => 12,
    'name' => 'Blockchain Development',
    'client' => 'Lambda Blockchain',
    'start_date' => '2024-11-01',
    'due_date' => '2025-02-15',
    'status' => 'ongoing',
    'end_date' => '',
    'description' => 'Developing a blockchain solution for Lambda Blockchain.'
  ],
  [
    'id' => 13,
    'name' => 'AI Model Training',
    'client' => 'Mu AI',
    'start_date' => '2024-12-01',
    'due_date' => '2025-03-15',
    'status' => 'finished',
    'end_date' => '2025-03-20',
    'description' => 'Training an AI model for Mu AI\'s new product.'
  ],
  [
    'id' => 14,
    'name' => 'E-commerce Platform Development',
    'client' => 'Nu Commerce',
    'start_date' => '2025-01-01',
    'due_date' => '2025-04-15',
    'status' => 'ongoing',
    'end_date' => '',
    'description' => 'Developing a new e-commerce platform for Nu Commerce.'
  ],
  [
    'id' => 15,
    'name' => 'Virtual Reality Experience',
    'client' => 'Xi VR',
    'start_date' => '2025-02-01',
    'due_date' => '2025-05-15',
    'status' => 'finished',
    'end_date' => '2025-05-20',
    'description' => 'Creating a virtual reality experience for Xi VR.'
  ],
  [
    'id' => 16,
    'name' => 'IoT Device Development',
    'client' => 'Omicron IoT',
    'start_date' => '2025-03-01',
    'due_date' => '2025-06-15',
    'status' => 'ongoing',
    'end_date' => '',
    'description' => 'Developing IoT devices for Omicron IoT.'
  ],
  [
    'id' => 17,
    'name' => 'Digital Marketing Strategy',
    'client' => 'Pi Marketing',
    'start_date' => '2025-04-01',
    'due_date' => '2025-07-15',
    'status' => 'finished',
    'end_date' => '2025-07-20',
    'description' => 'Creating a digital marketing strategy for Pi Marketing.'
  ],
  [
    'id' => 18,
    'name' => 'Game Development Project',
    'client' => 'Rho Games',
    'start_date' => '2025-05-01',
    'due_date' => '2025-08-15',
    'status' => 'ongoing',
    'end_date' => '',
    'description' => 'Developing a new game for Rho Games.'
  ],
  [
    'id' => 19,
    'name' => 'Network Infrastructure Upgrade',
    'client' => 'Sigma Networks',
    'start_date' => '2025-06-01',
    'due_date' => '2025-09-15',
    'status' => 'finished',
    'end_date' => '2025-09-20',
    'description' => 'Upgrading the network infrastructure for Sigma Networks.'
  ],
  [
    'id' => 20,
    'name' => "Client's Project",
    'client' => "Client's Company",
    'start_date' => '2024-08-01',
    'due_date' => '2024-12-31',
    'status' => 'ongoing',
    'end_date' => '',
    'description' => 'A project specifically for the client.'
  ]
];
?>
