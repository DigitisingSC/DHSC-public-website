import contentListingSection from './content-listing-section.twig';
import './../../../../../custom/dhsc_theme/css/components/content-listing-section.css';
export default {
  title: 'Design System/Molecules/Content Listing Section'
}

const Template = ({ section_title, section_links}) =>
  contentListingSection({
    section_title,
    section_links
  });

export const ContentListingSection = Template.bind({});
ContentListingSection.args = {
  section_title: 'How to use mobiles and tablets',
  section_links: [{"section_link":{"title":"Support for using Apple devices","href":"\/page"},"child_links":[{"title":"How to change your passcode","href":"\/page\/p"},{"title":"How to set up an Apple ID","href":"\/page\/p-2"},{"title":"How to download an App from the App Store","href":"\/page\/p-2"}]},{"section_link":{"title":"Choosing mobile device management","href":"\/page"},"child_links":[]},{"section_link":{"title":"How to get the most out of iPads","href":"\/page"},"child_links":[{"title":"Top tips for using iPads","href":"\/page\/p"}]}],

}
