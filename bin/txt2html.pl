#!/usr/bin/perl
#
# txt2html.pl
# Convert raw text to something with a little HTML formatting
#
# Written by Seth Golub <seth@aigeek.com> 
#            http://www.aigeek.com/txt2html/
#
# $Revision: 1.28 $
# $Date: 2000/05/22 23:44:58 $
#


#################################################################
# Some initializations that need to go before the configuration
#

@links_dictionaries = 0;
$num_heading_styles = 0;

#
#########################


#########################
# Configurable options
#

# [-s <n>    ] | [--shortline <n>                 ]
$short_line_length = 40;        # Lines this short (or shorter) must be
                                # intentionally broken and are kept
                                # that short. <BR>

# [-p <n>    ] | [--prewhite <n>                  ]
$preformat_whitespace_min = 5;  # Minimum number of consecutive 
                                # whitespace characters to trigger 
                                # preformatting.  
                                # NOTE: Tabs are now expanded to
                                # spaces before this check is made. 
                                # That means if $tab_width is 8 and
                                # this is 5, then one tab may be
                                # expanded to 8 spaces, which is
                                # enough to trigger preformatting.

$par_indent = 2;                # Minumum number of spaces indented in 
                                # first lines of paragraphs.
                                #   Only used when there's no blank line
                                # preceding the new paragraph.  (Like in
                                # this comment)

# [-pb <n>   ] | [--prebegin <n>                  ]
$preformat_trigger_lines = 2;   # How many lines of preformatted-looking
                                # text are needed to switch to <PRE>
                                # <= 0 : Preformat entire document
                                #    1 : one line triggers
                                # >= 2 : two lines trigger

# [-pe <n>   ] | [--preend <n>                    ]
$endpreformat_trigger_lines = 2; # How many lines of unpreformatted-looking
                                 # text are needed to switch from <PRE>
                                 # <= 0 : Never preformat within document
                                 #    1 : one line triggers
                                 # >= 2 : two lines trigger
# NOTE for --prebegin and --preend:
# A zero takes precedence.  If one is zero, the other is ignored.
# If both are zero, entire document is preformatted.

# [-r <n>    ] | [--hrule <n>                     ]
$hrule_min = 4;                 # Min number of ---s for an HRule.

# [-c <n>    ] | [--caps <n>                      ]
$min_caps_length = 3;           # min sequential CAPS for an all-caps line

# [-ct <tag> ] | [--capstag <tag>                 ]
$caps_tag = "STRONG";           # Tag to put around all-caps lines

# [-m/+m     ] | [--mail        / --nomail        ]
$mailmode = 0;                  # Deal with mail headers & quoted text

# [-u/+u     ] | [--unhyphenate / --nounhyphenate ]
$unhyphenation = 1;             # Enables unhyphenation of text.

# [-a <file> ] | [--append <file>                 ]
# [-ab <file>] | [--append_body <file>            ]
# [+a        ] | [--noappend                      ]
# [+ab <file>] | [--noappend_body <file>          ]
$append_file = 0;               # If you want something appended by 
                                # default, put the filename here.
                                # The appended text will not be
                                # processed at all, so make sure it's
                                # plain text or decent HTML.  i.e. do
                                # not have things like:
                                #   Seth Golub <seth@cs.wustl.edu>
                                # but instead, have:
                                #   Seth Golub &lt;seth@cs.wustl.edu&gt;

# [-pp <file> ] | [--prepend_body <file>                 ]
# [+pp        ] | [--noprepend_body <file>                 ]
$prepend_file = 0;              # Same sort of thing, but goes before
                                # the processed body text, rather than after.

# [-ah <file> ] | [--append_head <file>                 ]
# [+ah        ] | [--noappend_head                      ]
$append_head = 0;               # If you want something appended to
                                # the head by default, put the
                                # filename here.  The appended text
                                # will not be processed at all, so
                                # make sure it's plain text or decent
                                # HTML. i.e.  do not have things like:
                                #   Seth Golub <seth@cs.wustl.edu>
                                # but instead, have:
                                #   Seth Golub &lt;seth@cs.wustl.edu&gt;

# [-t <title>] | [--title <title>                 ]
$title = 0;                     # You can specify a title.
                                # Otherwise it will use a blank one.

# [-tf/+tf   ] | [--titlefirst / --notitlefirst   ]
$titlefirst = 0;                # Use the first non-blank line as the title

# [-dt <doct> ] | [--doctype <doctype>             ]
# [+dt        ] | [--nodoctype                     ]
$doctype = "-//W3C//DTD HTML 3.2 Final//EN";
                                # This gets put in the DOCTYPE field at the
                                # top of the document, unless it's 0.

# [-ul <n>   ] | [--ulength <n>             ]
$underline_length_tolerance = 1; # How much longer or shorter can 
                                 # underlines be and still be underlines?

# [-uo <n>   ] | [--uoffset <n>            ]
$underline_offset_tolerance = 1; # How far offset can underlines 
                                 # be and still be underlines?

# [-tw <n>   ] | [--tabwidth <n>                  ]
$tab_width = 8;                 # How many spaces equal a tab?


# [-iw <n>   ] | [--indent <n>                    ]
$indent_width = 2;              # Indents this many spaces for each 
                                # level of a list

# [-/+e      ] | [--extract / --noextract         ]
$extract = 0;                   # Extract Mode (suitable for inserting)

# [-l <file> ] | [--link <dictfile>               ]
# [+l        ] | [--nolink                        ]
$make_links = 1;                # Should we try to link anything?

# [-ec/+ec   ] | [--escapechars / --noescapechars ]
$escape_HTML_chars = 1;         # turn & < > into &amp; &gt; &lt;

# [-8/+8     ] | [--8-bit-clean / --no-8-bit-clean ]
$eight_bit_clean = 0;           # disable Latin-1 character entity naming

# [-LO/+LO    ] | [--linkonly / --nolinkonly       ]
$link_only = 0;                 # Do no escaping or marking up at all, 
                                # except for processing the links
                                # dictionary file and applying it. 
                                # This is useful if you want to use
                                # txt2html's linking feature on an
                                # HTML document.  If the HTML is a
                                # complete document (includes
                                # HTML,HEAD,BODY tags, etc) then you'll
                                # probably want to use the --extract
                                # option also.

# [-H <regexp>] | [--heading <regexp>              ]
@custom_heading_regexp = ();    # Add a regexp for headings.
                                # Header levels are assigned by regexp
                                # in order seen When a line matches a
                                # custom header regexp, it is tagged as
                                # a header.  If it's the first time
                                # that particular regexp has matched,
                                # the next available header level is
                                # associated with it and applied to
                                # the line.  Any later matches of that
                                # regexp will use the same header level.
                                # Therefore, if you want to match
                                # numbered header lines, you could use
                                # something like this:
# -H '^ *\d+\. \w+' -H '^ *\d+\.\d+\. \w+' -H '^ *\d+\.\d+\.\d+\. \w+'
                                # Then lines like " 1. Examples "
                                #                 " 1.1 Things"
                                #             and " 4.2.5 Cold Fusion"
                                # Would be marked as H1, H2, and H3
                                # (assuming they were found in that
                                # order, and that no other header
                                # styles were encountered).
                                # If you prefer that the first one 
                                # specified always be H1, the second
                                # always be H2, the third H3, etc,
                                # then use the -EH/--explicit-headings
                                # option.

# [-EH/+EH    ] | [--explicit_headings / --noexplicit_headings ]
$explicit_headings = 0;         # Don't try to find any headings
                                # except the custom one specified.
                                # Also, the custom headings will not
                                # be assigned levels in the order they
                                # are encountered in the document, but
                                # in the order they are specified on
                                # the command line.


# Not implemented yet.
# [-T <t>:<r> ] | [--tag <tagname>:<regexp>        ]
@custom_tags = ();              # Similar to --heading, this lets you
                                # specify arbitrary patterns to tag. 
                                # The first subexpression, if one is
                                # present, will replace the entire
                                # matched text.  Example:
                                # "em:\*(\w+)\*" will match any word
                                # surrounded by asterisks and mark it
                                # as emphasized, removing the
                                # asterisks.

# [-db <n>   ] | [--debug <n>                      ]
$dict_debug = 0;                # Debug mode for link dictionaries
                                # Bitwise-Or what you want to see:
                                # 1: The parsing of the dictionary
                                # 2: The code that will make the links
                                # 4: When each rule matches something

$system_link_dict = "/usr/local/lib/txt2html.dict"; # after options
$default_link_dict = "$ENV{'HOME'}/.txt2html.dict"; # before options

# [-pm/+pm    ] | [--preformat-marker / --nopreformat-marker ]
$use_preformat_marker = 0;      # Turn on preformatting when encountering
                                # "<PRE>" on a line by itself, and turn it
                                # off when there's a line containing only 
                                # "</PRE>".

$preformat_start_marker = "^(:?(:?&lt;)|<)PRE(:?(:?&gt;)|>)\$";
$preformat_end_marker   = "^(:?(:?&lt;)|<)/PRE(:?(:?&gt;)|>)\$";

# Uncomment the following lines if you want to force the heading
# styles to match what Mosaic outputs.  (Underlined with "***"s is H1,
# with "==="s is H2, etc.)  This was the behavior of txt2html up to
# version 1.10.
#
#$heading_styles{"*"} = ++$num_heading_styles;
#$heading_styles{"="} = ++$num_heading_styles;
#$heading_styles{"+"} = ++$num_heading_styles;
#$heading_styles{"-"} = ++$num_heading_styles;
#$heading_styles{"~"} = ++$num_heading_styles;
#$heading_styles{"."} = ++$num_heading_styles;

# END OF CONFIGURABLE OPTIONS
########################################


########################################
# Definitions  (Don't change these)
#


# These are just constants I use for making bit vectors to keep track
# of what modes I'm in and what actions I've taken on the current and
# previous lines.  

$NONE       =   0;
$LIST       =   1;
$HRULE      =   2;
$PAR        =   4;
$PRE        =   8;
$END        =  16;
$BREAK      =  32;
$HEADER     =  64;
$MAILHEADER = 128;
$MAILQUOTE  = 256;
$CAPS       = 512;
$LINK       =1024;
$PRE_EXPLICIT = 2048;


# Constants for Ordered Lists and Unordered Lists.  
# I use this in the list stack to keep track of what's what.

$OL = 1;
$UL = 2;


# Character entity names
# characters to replace *before* processing a line
%char_entities = ( 
     "\241", "&iexcl;",  "\242", "&cent;",   "\243", "&pound;",
     "\244", "&curren;", "\245", "&yen;",    "\246", "&brvbar;",
     "\247", "&sect;",   "\250", "&uml;",    "\251", "&copy;",
     "\252", "&ordf;",   "\253", "&laquo;",  "\254", "&not;",
     "\255", "&shy;",    "\256", "&reg;",    "\257", "&hibar;",
     "\260", "&deg;",    "\261", "&plusmn;", "\262", "&sup2;",
     "\263", "&sup3;",   "\264", "&acute;",  "\265", "&micro;",
     "\266", "&para;",                       "\270", "&cedil;",
     "\271", "&sup1;",   "\272", "&ordm;",   "\273", "&raquo;",
     "\274", "&fraq14;", "\275", "&fraq12;", "\276", "&fraq34;",
     "\277", "&iquest;", "\300", "&Agrave;", "\301", "&Aacute;", 
     "\302", "&Acirc;",  "\303", "&Atilde;", "\304", "&Auml;",
     "\305", "&Aring;",  "\306", "&AElig;",  "\307", "&Ccedil;", 
     "\310", "&Egrave;", "\311", "&Eacute;", "\312", "&Ecirc;", 
     "\313", "&Euml;",   "\314", "&Igrave;", "\315", "&Iacute;", 
     "\316", "&Icirc;",  "\317", "&Iuml;",   "\320", "&ETH;", 
     "\321", "&Ntilde;", "\322", "&Ograve;", "\323", "&Oacute;",
     "\324", "&Ocirc;",  "\325", "&Otilde;", "\326", "&Ouml;", 
     "\327", "&times;",  "\330", "&Oslash;", "\331", "&Ugrave;",
     "\332", "&Uacute;", "\333", "&Ucirc;",  "\334", "&Uuml;", 
     "\335", "&Yacute;", "\336", "&THORN;",  "\337", "&szlig;", 
     "\340", "&agrave;", "\341", "&aacute;", "\342", "&acirc;", 
     "\343", "&atilde;", "\344", "&auml;",   "\345", "&aring;", 
     "\346", "&aelig;",  "\347", "&ccedil;", "\350", "&egrave;", 
     "\351", "&eacute;", "\352", "&ecirc;",  "\353", "&euml;", 
     "\354", "&igrave;", "\355", "&iacute;", "\356", "&icirc;",
     "\357", "&iuml;",   "\360", "&eth;",    "\361", "&ntilde;",
     "\362", "&ograve;", "\363", "&oacute;", "\364", "&ocirc;", 
     "\365", "&otilde;", "\366", "&ouml;",   "\367", "&divide;",
     "\370", "&oslash;", "\371", "&ugrave;", "\372", "&uacute;",
     "\373", "&ucirc;",  "\374", "&uuml;",   "\375", "&yacute;", 
     "\376", "&thorn;",  "\377", "&yuml;", 
    );

# characters to replace *after* processing a line
%char_entities2 = ( "\267", "&middot;", );

$version = '$Revision: 1.28 $ ';  $version =~ s/.*(\d+\.\S+).*/$1/;


########################################
########################################
#
# Subroutine definitions

sub usage
{
    $0 =~ s#.*/##;
    print STDERR <<EOUsage;

Usage: $0 [options]

where options are:
     [-v         ] | [--version                       ]
     [-h         ] | [--help                          ]
     [-t <title> ] | [--title <title>                 ]
     [-tf/+tf    ] | [--titlefirst / --notitlefirst   ]
     [-dt <doct> ] | [--doctype <doctype>             ]
     [+dt        ] | [--nodoctype                     ]
     [-l <file>  ] | [--link <dictfile>               ]
     [+l         ] | [--nolink                        ]
     [-H <regexp>] | [--heading <regexp>              ]
     [-EH/+EH    ] | [--explicit_headings / --noexplicit_headings ]
     [-ab <file> ] | [--append_body <file>            ]
     [+ab        ] | [--noappend_body                 ]
     [-ah <file> ] | [--append_head <file>            ]
     [+ah        ] | [--noappend_head                 ]
     [-pp <file> ] | [--prepend_body <file>           ]
     [+pp        ] | [--noprepend_body <file>         ]
     [-ec/+ec    ] | [--escapechars / --noescapechars ]
     [-8/+8      ] | [--8-bit-clean / --no-8-bit-clean ]
     [-e/+e      ] | [--extract / --noextract         ]
     [-c <n>     ] | [--caps <n>                      ]
     [-ct <tag>  ] | [--capstag <tag>                 ]
     [-m/+m      ] | [--mail     / --nomail           ]
     [-u/+u      ] | [--unhyphen / --nounhyphen       ]
     [-ul <n>    ] | [--ulength <n>                   ]
     [-uo <n>    ] | [--uoffset <n>                   ]
     [-tw <n>    ] | [--tabwidth <n>                  ]
     [-iw <n>    ] | [--indent <n>                    ]
     [-s <n>     ] | [--shortline <n>                 ]
     [-p <n>     ] | [--prewhite <n>                  ]
     [-pb <n>    ] | [--prebegin <n>                  ]
     [-pe <n>    ] | [--preend <n>                    ]
     [-r <n>     ] | [--hrule <n>                     ]
     [-LO/+LO    ] | [--linkonly / --nolinkonly       ]
     [-db <n>    ] | [--debug <n>                     ]
     [-pm/+pm    ] | [--preformat-marker / --nopreformat-marker ]

  More complete explanations of these options can be found in 
  comments near the beginning of the script.

EOUsage
#     [-T <t>:<r> ] | [--tag <tagname>:<regexp>        ]
}


sub deal_with_options
{
    while (( $#ARGV > -1 ) && ( $ARGV[0] =~ /^[-+].+/ ) )
    {
        if (($ARGV[0] eq "-l" || $ARGV[0] eq "--link") &&
            $ARGV[1])
        {
            if (-r $ARGV[1]) {
                $make_links = 1;
                # Stick it on the end of the list
                push(@links_dictionaries, $ARGV[1]);
            } else {
                print STDERR "Can't find or read link-file $ARGV[1].\n";
            }
            shift @ARGV;
            next;
        }

        if (($ARGV[0] eq "+l" || $ARGV[0] eq "--nolink") )
        {
            $system_link_dict = "";
            $make_links = 0;
            @links_dictionaries = 0;
            next;
        }

        if (($ARGV[0] eq "-H" || $ARGV[0] eq "--heading") &&
            $ARGV[1])
        {
            push(@custom_heading_regexp, $ARGV[1]);
            shift @ARGV;
            next;
        }

        if (($ARGV[0] eq "-EH" || $ARGV[0] eq "--explicit_headings") )
        {
            $explicit_headings = 1;
            next;
        }

        if (($ARGV[0] eq "+EH" || $ARGV[0] eq "--noexplicit_headings") )
        {
            $explicit_headings = 0;
            next;
        }

        if (($ARGV[0] eq "-T" || $ARGV[0] eq "--tag") &&
            $ARGV[1])
        {
            print STDERR "Sorry.  $ARGV[0] isn't supported yet.\n";
            push(@custom_tags, $ARGV[1]);
            shift @ARGV;
            next;
        }

        if (($ARGV[0] eq "-r" || $ARGV[0] eq "--hrule") &&
            $ARGV[1] =~ /^\d+$/)
        {
            $hrule_min = $ARGV[1];
            shift @ARGV;
            next;
        }

        if (($ARGV[0] eq "-s" || $ARGV[0] eq "--shortline") &&
            $ARGV[1] =~ /^\d+$/)
        {
            $short_line_length = $ARGV[1];
            shift @ARGV;
            next;
        }

        if (($ARGV[0] eq "-p" || $ARGV[0] eq "--prewhite") &&
            $ARGV[1] =~ /^\d+$/)
        {
            $preformat_whitespace_min = $ARGV[1];
            shift @ARGV;
            next;
        }

        if (($ARGV[0] eq "-pb" || $ARGV[0] eq "--prebegin") &&
            $ARGV[1] =~ /^-?\d+$/)
        {
            $preformat_trigger_lines = $ARGV[1];
            shift @ARGV;
            next;
        }
    
        if (($ARGV[0] eq "-pe" || $ARGV[0] eq "--preend") &&
            $ARGV[1] =~ /^-?\d+$/)
        {
            $endpreformat_trigger_lines = $ARGV[1];
            shift @ARGV;
            next;
        }

        if (($ARGV[0] eq "-pm" || $ARGV[0] eq "--preformat-marker"))
        {
            $use_preformat_marker = 1;
            next;
        }

        if (($ARGV[0] eq "+pm" || $ARGV[0] eq "--no-preformat-marker"))
        {
            $use_preformat_marker = 0;
            next;
        }

        if (($ARGV[0] eq "-e" || $ARGV[0] eq "--extract"))
        {
            $extract = 1;
            next;
        }

        if (($ARGV[0] eq "+e" || $ARGV[0] eq "--noextract"))
        {
            $extract = 0;
            next;
        }

        if (($ARGV[0] eq "-c" || $ARGV[0] eq "--caps") &&
            $ARGV[1] =~ /^\d+$/)
        {
            $min_caps_length = $ARGV[1];
            shift @ARGV;
            next;
        }

        if (($ARGV[0] eq "-ct" || $ARGV[0] eq "--capstag") &&
            $ARGV[1])
        {
            $caps_tag = $ARGV[1];
            shift @ARGV;
            next;
        }

        if ($ARGV[0] eq "-m" || $ARGV[0] eq "--mail")
        {
            $mailmode = 1;
            next;
        }

        if ($ARGV[0] eq "+m" || $ARGV[0] eq "--nomail")
        {
            $mailmode = 0;
            next;
        }

        if ($ARGV[0] eq "-u" || $ARGV[0] eq "--unhyphen")
        {
            $unhyphenation = 1;
            next;
        }

        if ($ARGV[0] eq "+u" || $ARGV[0] eq "--nounhyphen")
        {
            $unhyphenation = 0;
            next;
        }

        if (($ARGV[0] eq "-a" || $ARGV[0] eq "-ab" ||
             $ARGV[0] eq "--append" || $ARGV[0] eq "--append_body") &&
            $ARGV[1])
        {
            if (-r $ARGV[1]) {
                $append_file = $ARGV[1];
            } else {
                print STDERR "Can't find or read $ARGV[1].\n";
            }
            shift @ARGV;
            next;
        }

        if ($ARGV[0] eq "+a" || $ARGV[0] eq "+ab" ||
            $ARGV[0] eq "--noappend" || $ARGV[0] eq "--noappend_body")
        {
            $append_file = 0;
            next;
        }

        if (($ARGV[0] eq "-pp" ||
             $ARGV[0] eq "--prepend" || $ARGV[0] eq "--prepend_body")
            && $ARGV[1])
        {
            if (-r $ARGV[1]) {
                $prepend_file = $ARGV[1];
            } else {
                print STDERR "Can't find or read $ARGV[1].\n";
            }
            shift @ARGV;
            next;
        }

        if ($ARGV[0] eq "+pp" ||
            $ARGV[0] eq "--noprepend" || $ARGV[0] eq "--noprepend_body")
        {
            $prepend_file = 0;
            next;
        }

        if (($ARGV[0] eq "-ah" || $ARGV[0] eq "--append_head") &&
            $ARGV[1])
        {
            if (-r $ARGV[1]) {
                $append_head = $ARGV[1];
            } else {
                print STDERR "Can't find or read $ARGV[1].\n";
            }
            shift @ARGV;
            next;
        }

        if ($ARGV[0] eq "+ah" || $ARGV[0] eq "--noappend_head")
        {
            $append_head = 0;
            next;
        }

        if (($ARGV[0] eq "-t" || $ARGV[0] eq "--title") &&
            $ARGV[1])
        {
            $title = $ARGV[1];
            shift @ARGV;
            next;
        }

        if ($ARGV[0] eq "-tf" || $ARGV[0] eq "--titlefirst")
        {
            $titlefirst = 1;
            next;
        }

        if ($ARGV[0] eq "+tf" || $ARGV[0] eq "--notitlefirst")
        {
            $titlefirst = 0;
            next;
        }

        if (($ARGV[0] eq "-dt" || $ARGV[0] eq "--doctype") &&
            $ARGV[1])
        {
            $doctype = $ARGV[1];
            shift @ARGV;
            next;
        }

        if ($ARGV[0] eq "+dt" || $ARGV[0] eq "--nodoctype")
        {
            $doctype = 0;
            next;
        }

        if (($ARGV[0] eq "-ul" || $ARGV[0] eq "--ulength") &&
            $ARGV[1] =~ /^\d+$/)
        {
            $underline_length_tolerance = $ARGV[1];
            shift @ARGV;
            next;
        }

        if (($ARGV[0] eq "-uo" || $ARGV[0] eq "--uoffset") &&
            $ARGV[1] =~ /^\d+$/)
        {
            $underline_offset_tolerance = $ARGV[1];
            shift @ARGV;
            next;
        }

        if (($ARGV[0] eq "-tw" || $ARGV[0] eq "--tabwidth") &&
            $ARGV[1] =~ /^\d+$/)
        {
            $tab_width = $ARGV[1];
            shift @ARGV;
            next;
        }

        if (($ARGV[0] eq "-iw" || $ARGV[0] eq "--indentwidth") &&
            $ARGV[1] =~ /^\d+$/)
        {
            $indent_width = $ARGV[1];
            shift @ARGV;
            next;
        }

        if ($ARGV[0] eq "-ec" || $ARGV[0] eq "--escapechars")
        {
            $escape_HTML_chars = 1;
            next;
        }

        if ($ARGV[0] eq "+ec" || $ARGV[0] eq "--noescapechars")
        {
            $escape_HTML_chars = 0;
            next;
        }

        if ($ARGV[0] eq "-8" || $ARGV[0] eq "--8-bit-clean")
        {
            $eight_bit_clean = 1;
            next;
        }

        if ($ARGV[0] eq "+8" || $ARGV[0] eq "--no-8-bit-clean")
        {
            $eight_bit_clean = 0;
            next;
        }

        if ($ARGV[0] eq "-LO" || $ARGV[0] eq "--linkonly")
        {
            $link_only = 1;
            next;
        }

        if ($ARGV[0] eq "+LO" || $ARGV[0] eq "--nolinkonly")
        {
            $link_only = 0;
            next;
        }

        if ($ARGV[0] eq "-v" || $ARGV[0] eq "--version")
        {
            print "txt2html $version\n";
            exit;
        }

        if ($ARGV[0] eq "-h" || $ARGV[0] eq "--help")
        {
            &usage;
            exit;
        }

        if (($ARGV[0] eq "-db" || $ARGV[0] eq "--debug") &&
            $ARGV[1] =~ /^\d+$/)
        {
            $dict_debug = $ARGV[1];
            shift @ARGV;
            next;
        }

        if ($ARGV[0] eq "--")
        {
            last;
        }

        print STDERR "Unrecognized option: $ARGV[0]\n";
        print STDERR " or bad paramater: $ARGV[1]\n" if($ARGV[1]);

        &usage;
        exit(1);

    } continue {

        shift @ARGV;
    }

    $preformat_trigger_lines = 0 if ($preformat_trigger_lines < 0);
    $preformat_trigger_lines = 2 if ($preformat_trigger_lines > 2);

    $endpreformat_trigger_lines = 1 if ($preformat_trigger_lines == 0);
    $endpreformat_trigger_lines = 0 if ($endpreformat_trigger_lines < 0);
    $endpreformat_trigger_lines = 2 if ($endpreformat_trigger_lines > 2);

    $preformat_enabled = ( ($endpreformat_trigger_lines != 0)
                           || $use_preformat_marker );
}

sub is_blank
{
    return $_[0] =~ /^\s*$/;
}

sub escape
{
  local ( $text ) = @_;
  $text =~ s/&/&amp;/g;
  $text =~ s/>/&gt;/g;
  $text =~ s/</&lt;/g;
  return $text
}

sub hrule
{
    if ($line =~ /^\s*([-_~=\*]\s*){$hrule_min,}$/)
    {
        $line = "<HR>\n";
        $prev =~ s/<P>//;
        $line_action |= $HRULE;
    } elsif ($line =~ /\014/)
    {
        $line_action |= $HRULE;
        $line =~ s/\014/\n<HR>\n/g; # Linefeeds become horizontal rules
    }
}

sub shortline
{

    # Short lines should be broken even on list item lines iff the
    # following line is more text.  I haven't figured out how to do
    # that yet.  For now, I'll just not break on short lines in lists.
    # (sorry)

    if (!($mode & ($PRE | $LIST))
        && !&is_blank($line)
        && !&is_blank($prev) 
        && ($prev_line_length < $short_line_length) 
        && !($line_action & ($END | $HEADER | $HRULE | $LIST | $PAR))
        && !($prev_action & ($HEADER | $HRULE | $BREAK)))
    {
        $prev .= "<BR>" . chop($prev);
        $prev_action |= $BREAK;
    }
}

sub mailstuff
{
    if ((($line =~ /^\w*&gt/)    # Handle "FF> Werewolves."
         || ($line =~ /^\w*\|/)) # Handle "Igor| There wolves."
        && !&is_blank($nextline))
    {
        $line =~ s/$/<BR>/;
        $line_action |= ($BREAK | $MAILQUOTE);
        if(!($prev_action & ($BREAK | $PAR)))
        {
            $prev .= "<P>\n";
            $line_action |= $PAR;
        }
    } elsif (($line =~ /^(From:?)|(Newsgroups:) /)
             && &is_blank($prev))
    {
        &anchor_mail if !($prev_action & $MAILHEADER);
        chomp $line;
        $line = "<!-- New Message -->\n<p>\n" . $line . "<BR>\n";        
        $line_action |= ($BREAK | $MAILHEADER | $PAR);
    } elsif (($line =~ /^[\w\-]*:/)  # Handle "Some-Header: blah"
             && ($prev_action & $MAILHEADER) 
             && !&is_blank($nextline))
    {
        $line =~ s/$/<BR>/;
        $line_action |= ($BREAK | $MAILHEADER);
    } elsif (($line =~ /^\s+\S/) &&   # Handle multi-line mail headers
             ($prev_action & $MAILHEADER) &&
             !&is_blank($nextline))
    {
        $line =~ s/$/<BR>/;
        $line_action |= ($BREAK | $MAILHEADER);
    }
}

# Subtracts modes listed in $mask from $vector.
sub subtract_modes
{
    local($vector, $mask) = @_;
    ($vector | $mask) - $mask;
}

sub paragraph
{
    if(!&is_blank($line)
       && !($mode & $PRE)
       && !&subtract_modes($line_action, $END | $MAILQUOTE | $CAPS | $BREAK)
       && (&is_blank($prev) 
           || ($line_action & $END)
           || ($line_indent > $prev_indent + $par_indent)))
    {
        $prev .= "<P>\n";
        $line_action |= $PAR;
    }
}

# If the line is blank, return the second argument.  Otherwise,
# return the number of spaces before any nonspaces on the line.
sub count_indent
{
    local($line, $prev_length) = @_;
    if(&is_blank($line))
    {
        return $prev_length;
    }
    local($ws) = $line =~ /^( *)[^ ]/;
    length($ws);
}

sub listprefix
{
    local($line) = @_;
    local($prefix, $number, $rawprefix);

    return (0,0,0) if (!($line =~ /^\s*[-=o\*\267]+\s+\S/ ) &&
                       !($line =~ /^\s*(\d+|[^\W\d_])[\.\)\]:]\s+\S/ ));

    ($number) = $line =~ /^\s*(\d+|[^\W\d_])/;
    $number = 0 unless defined( $number );

    # That slippery exception of "o" as a bullet
    # (This ought to be determined using the context of what lists
    #  we have in progress, but this will probably work well enough.)
    if($line =~ /^\s*o\s/)
    {
        $number = 0;
    }

    if ($number)
    {
        ($rawprefix) = $line =~ /^(\s*(\d+|[^\W\d_]).)/;
        $prefix = $rawprefix;
        $prefix =~ s/(\d+|[^\W\d_])//;  # Take the number out
    } else {
        ($rawprefix) = $line =~ /^(\s*[-=o\*\267]+.)/;
        $prefix = $rawprefix;
    }
    ($prefix, $number, $rawprefix);
}

sub startlist
{
    local($prefix, $number, $rawprefix) = @_;

    $listprefix[$listnum] = $prefix;
    if($number)
    {
        # It doesn't start with 1,a,A.  Let's not screw with it.
        if (($number ne "1") && ($number ne "a") && ($number ne "A"))
        {
            return 0;
        }
        $prev .= "$list_indent<OL>\n";
        $list[$listnum] = $OL;
    } else {
        $prev .= "$list_indent<UL>\n";
        $list[$listnum] = $UL;
    }

    $listnum++;
    $list_indent = " " x $listnum x $indent_width;
    $line_action |= $LIST;
    $mode |= $LIST;
    1;
}


sub endlist                     # End N lists
{
    local($n) = @_;
    for(; $n > 0; $n--, $listnum--)
    {
        $list_indent = " " x ($listnum-1) x $indent_width;
        if($list[$listnum-1] == $UL)
        {
            $prev .= "$list_indent</UL>\n";
        } elsif($list[$listnum-1] == $OL)
        {
            $prev .= "$list_indent</OL>\n";
        } else
        {
            print STDERR "Encountered list of unknown type\n";
        }
    }
    $line_action |= $END;
    $mode ^= $LIST if (!$listnum);
}

sub continuelist
{
    $line =~ s/^\s*[-=o\*\267]+\s*/$list_indent<LI>/ if $list[$listnum-1] == $UL;
    $line =~ s/^\s*(\d+|[^\W\d_]).\s*/$list_indent<LI>/
        if $list[$listnum-1] == $OL;
    $line_action |= $LIST;
}

sub liststuff
{
    local($i);

    local($prefix, $number, $rawprefix) = &listprefix($line);

    if (!$prefix)
    {
        return if !&is_blank($prev); # inside a list item
        # This ain't no list.  We'll want to end all of them.
        &endlist($listnum) if $listnum;
        return;
    }
    
    # If numbers with more than one digit grow to the left instead of
    # to the right, the prefix will shrink and we'll fail to match the
    # right list.  We need to account for this.
    local ( $prefix_alternate );
    if ( length( "" . $number ) > 1 )
    {
        $prefix_alternate = ( " " x ( length( "" . $number ) -1 )) . $prefix;
    }
    
    # Maybe we're going back up to a previous list
    for($i = $listnum - 1; ($i >= 0) && ($prefix ne $listprefix[$i]); $i--)
    {
        if ( length( "" . $number ) > 1 )
        {
            last if $prefix_alternate eq $listprefix[$i];
        }
    }

    local($islist);

    # Measure the indent from where the text starts, not where the
    # prefix starts.  This won't screw anything up, and if we don't do
    # it, the next line might appear to be indented relative to this
    # line, and get tagged as a new paragraph.
    local($total_prefix) = $line =~ /^(\s*[\w=o\*-]+.\s*)/;
    # Of course, we only use it if it really turns out to be a list.

    $islist = 1;
    $i++;
    if (($i > 0) && ($i != $listnum))
    { 
        &endlist($listnum - $i);
        $islist = 0;
    } elsif (!$listnum || ($i != $listnum))
    { 
        if ( ($line_indent > 0) 
            || &is_blank($prev) 
            || ($prev_action & ($BREAK | $HEADER | $CAPS)))
        {
            $islist = &startlist($prefix, $number, $rawprefix);
        } else 
        {
            # We have something like this: "- foo" which usually
            # turns out not to be a list.
            return;
        }
    }

    &continuelist($prefix, $number, $rawprefix) if ($mode & $LIST);
    $line_indent = length($total_prefix) if $islist;
}

# Returns true if the passed string is considered to be preformatted
sub is_preformatted
{
    (($_[0] =~ /\s{$preformat_whitespace_min,}\S+/o) # whitespaces
     || ($_[0] =~ /\.{$preformat_whitespace_min,}\S+/o)); # dots
}

sub endpreformat
{
    if( $mode & $PRE_EXPLICIT )
    {
      if ( $line =~ /$preformat_end_marker/io )
        {
          $prev .= "</PRE>\n";
          $line = "";
          $mode ^= (($PRE | $PRE_EXPLICIT) & $mode);
          $line_action |= $END;
        }
      return;
    }

    if(!&is_preformatted($line) 
       && ($endpreformat_trigger_lines == 1 
           || !&is_preformatted($nextline)))
    {
        $prev .= "</PRE>\n";
        $mode ^= ($PRE & $mode);
        $line_action |= $END;
    }
}

sub preformat
{
    if( $use_preformat_marker )
    {
      if ( $line =~ /$preformat_start_marker/io )
        {
          $line = "<PRE>\n";
          $prev =~ s/<P>//;
          $mode |= $PRE | $PRE_EXPLICIT;
          $line_action |= $PRE;
          return;
        }
    }

    if($preformat_trigger_lines == 0 
       || (&is_preformatted($line) &&
           ($preformat_trigger_lines == 1 || &is_preformatted($nextline))))
    {
        $line =~ s/^/<PRE>\n/;
        $prev =~ s/<P>//;
        $mode |= $PRE;
        $line_action |= $PRE;
    }
}

sub make_new_anchor
{
    local( $heading_level ) = @_;
    local($anchor, $i);

    return sprintf("%d", $non_header_anchor++) if(!$heading_level);

    $anchor = "section-";
    $heading_count[$heading_level-1]++;

    # Reset lower order counters
    for($i=$#heading_count + 1; $i > $heading_level; $i--)
    {
        $heading_count[$i-1] = 0;
    }

    for($i=0; $i < $heading_level; $i++)
    {
        $heading_count[$i] = 1 if !$heading_count[$i]; # In case they skip any
        $anchor .= sprintf("%d.", $heading_count[$i]);
    }
    chomp($anchor);
    $anchor;
}

sub anchor_mail
{
    local($anchor) = &make_new_anchor(0);
    $line =~ s/([^ ]*)/<A NAME="$anchor">$1<\/A>/;
}

sub anchor_heading
{
    local($level) = @_;
    local($anchor) = &make_new_anchor( $level );
    $line =~ s/(<H.>)(.*)(<\/H.>)/$1<A NAME="$anchor">$2<\/A>$3/;
}

sub heading_level
{
    local($style) = @_;
    $heading_styles{$style} = ++$num_heading_styles
        if !$heading_styles{$style};
    $heading_styles{$style};
}

sub heading
{
    local($hoffset, $heading) = $line =~ /^(\s*)(.+)$/;
    $hoffset = "" unless defined( $hoffset );
    $heading = "" unless defined( $heading );
    $heading =~ s/&[^;]+;/X/g;  # Unescape chars so we get an accurate length
    local($uoffset, $underline) = $nextline =~ /^(\s*)(\S+)\s*$/;
    $uoffset = "" unless defined( $uoffset );
    $underline = "" unless defined( $underline );
    local($lendiff, $offsetdiff);
    $lendiff = length($heading) - length($underline);
    $lendiff *= -1 if $lendiff < 0;

    $offsetdiff = length($hoffset) - length($uoffset);
    $offsetdiff *= -1 if $offsetdiff < 0;

    if(&is_blank($line)
       ||($lendiff > $underline_length_tolerance)
       ||($offsetdiff > $underline_offset_tolerance))
    {
        return;
    }

    $underline = substr($underline,0,1);

    $underline .= "C" if &iscaps($line); # Call it a different style if the
                                         # heading is in all caps.
    $nextline = &getline;             # Eat the underline
    $heading_level = &heading_level($underline);
    &tagline("H" . $heading_level);
    &anchor_heading( $heading_level );
    $line_action |= $HEADER;
}

sub custom_heading
{
    local($i, $level);
    for($i=0; $i <= $#custom_heading_regexp; $i++)
    {
        if ($line =~ /$custom_heading_regexp[$i]/)
        {
            if ( $explicit_headings )
            {
                $level = $i + 1;
            } else {
                $level = &heading_level("Cust" . $i);
            }
            &tagline("H" . $level);
            &anchor_heading( $level );
            $line_action |= $HEADER;
            last;
        }
    }
}

sub unhyphenate
{
    local($second);

    # This looks hairy because of all the quoted characters.
    # All I'm doing is pulling out the word that begins the next line.
    # Along with it, I pull out any punctuation that follows.
    # Preceding whitespace is preserved.  We don't want to screw up
    # our own guessing systems that rely on indentation.
    ($second) = $nextline =~ /^\s*([^\W\d_]+[\)\}\]\.,:;\'\"\>]*\s*)/; # "
    $nextline =~ s/^(\s*)[^\W\d_]+[\)\}\]\.,:;\'\"\>]*\s*/$1/; # "
    # (The silly comments are for my less-than-perfect code hilighter)

    $nextline = &getline if $nextline eq "";
    $line =~ s/\-\s*$/$second/;
    $line .= "\n";
}

sub untabify
{
    local($line) = @_;
    while($line =~ /\011/)
    {
        $line =~ s/\011/" " x ($tab_width - (length($`) % $tab_width))/e;
    }
    $line;
}

sub tagline
{
    local($tag) = @_;
    chomp $line;                 # Drop newline
    $line =~ s/^\s*(.*)$/<$tag>$1<\/$tag>\n/;
}

sub iscaps
{
    local($_) = @_;
    # This is ugly, but I don't know a better way to do it.
    # (And, yes, I could use the literal characters instead of the 
    # numeric codes, but this keeps the script 8-bit clean, which will
    # save someone a big headache when they transfer via ASCII ftp.
    /^[^a-z\341\343\344\352\353\354\363\370\337\373\375\342\345\347\350\355\357\364\365\376\371\377\340\346\351\360\356\361\362\366\372\374<]*[A-Z\300\301\302\303\304\305\306\307\310\311\312\313\314\315\316\317\320\321\322\323\324\325\326\330\331\332\333\334\335\336]{$min_caps_length,}[^a-z\341\343\344\352\353\354\363\370\337\373\375\342\345\347\350\355\357\364\365\376\371\377\340\346\351\360\356\361\362\366\372\374<]*$/;
}

sub caps
{
    if(&iscaps($line))
    {
        &tagline($caps_tag);
        $line_action |= $CAPS;
    }
}

# Convert very simple globs to regexps
sub glob2regexp
{
    local($glob) = @_;
    # Escape funky chars
    $glob =~ s/[^\w\[\]\*\?\|\\]/\\$&/g;
    local($regexp,$i,$len,$escaped) = ("",0,length($glob),0);
    
    for(;$i < $len; $i++)
    {
        $char = substr($glob,$i,1);
        if($escaped)
        {
            $escaped = 0;
            $regexp .= $char;
            next;
        }
        if ($char eq "\\") {
            $escaped = 1; next;
            $regexp .= $char;
        }
        if ($char eq "?") {
            $regexp .= "."; next;
        }
        if ($char eq "*") {
            $regexp .= ".*"; next;
        }
        $regexp .= $char;       # Normal character
    }
    "\\b" . $regexp . "\\b";
}

sub add_regexp_to_links_table
{
    local($key,$URL,$switches) = @_;
        # No sense adding a second one if it's already in there.
        # It would never get used.
        if(!$links_table{$key})
        {
            # Keep track of the order they were added so we can
            # look for matches in the same order
            push(@links_table_order, ($key));

            $links_table{$key} = $URL;        # Put it in The Table
            $links_switch_table{$key} = $switches;
            print STDERR 
 " ($#links_table_order)\tKEY: $key\n\tVALUE: $URL\n\tSWITCHES: $switches\n\n"
                if ($dict_debug & 1);
        } else 
        {
            if($dict_debug & 1) {
                print STDERR " Skipping entry.  Key already in table.\n";
                print STDERR "\tKEY: $key\n\tVALUE: $URL\n\n";
            }
        }
}

sub add_literal_to_links_table
{
    local($key,$URL,$switches) = @_;
    $key =~ s/(\W)/\\$1/g; # Escape non-alphanumeric chars
    $key = "\\b$key\\b"; # Make a regexp out of it
    &add_regexp_to_links_table($key,$URL,$switches);
}

sub add_glob_to_links_table
{
    local($key,$URL,$switches) = @_;
    &add_regexp_to_links_table(&glob2regexp($key),$URL,$switches);    
}

# This is the only function you should need to change if you want to
# use a different dictionary file format.
sub parse_dict
{
    local($dictfile, $dict) = @_;

    print STDERR "Parsing dictionary file $dictfile\n" if ($dict_debug & 1);

    $dict =~ s/^\#.*$//g;        # Strip lines that start with '#'
    $dict =~ s/^.*[^\\]:\s*$//g; # Strip lines that end with unescaped ':'

    if($dict =~ /->\s*->/)
    {
        $message = "Two consecutive '->'s found in $dictfile\n";

        # Print out any useful context so they can find it.
        ($near) = $dict =~ /([\S ]*\s*->\s*->\s*\S*)/;
        $message .= "\n$near\n" if $near =~ /\S/; 
        die $message;
    }

    while($dict =~ /\s*(.+)\s+\-+([ieho]+\-+)?\>\s*(.*\S+)\s*\n/ig)
    {
        local($key, $URL,$switches,$options);
        $key = $1;
        $options = $2;
        $options = "" unless defined($options);
        $URL = $3;
        $switches = 0;
        $switches += 1 if $options =~ /i/i; # Case insensitivity
        $switches += 2 if $options =~ /e/i; # Evaluate as Perl code
        $switches += 4 if $options =~ /h/i; # provides HTML, not just URL
        $switches += 8 if $options =~ /o/i; # Only do this link once

        $key =~ s/\s*$//;       # Chop trailing whitespace

        if($key =~ m|^/|)       # Regexp
        {
            $key = substr($key,1);
            $key =~ s|/$||;     # Allow them to forget the closing /
            &add_regexp_to_links_table($key,$URL,$switches);
        } elsif($key =~ /^\|/)  # alternate regexp format
        {
            $key = substr($key,1);
            $key =~ s/\|$//;    # Allow them to forget the closing |
            $key =~ s|/|\\/|g;  # Escape all slashes
            &add_regexp_to_links_table($key,$URL,$switches);
        } elsif ($key =~ /\"/)
        {
            $key = substr($key,1);
            $key =~ s/\"$//;    # Allow them to forget the closing "
            &add_literal_to_links_table($key,$URL,$switches);
        } else
        {
            &add_glob_to_links_table($key,$URL,$switches);
        }
    }
}

sub in_link_context
{
    local($match, $before) = @_;
    return 1 if $match =~ m@</?A>@i; # No links allowed inside match

    local($final_open, $final_close);
    $final_open = rindex($before, "<A ") - $[;
    $final_close = rindex($before, "</A>") - $[;

    return 1 if ($final_open >= 0) # Link opened 
        && (($final_close < 0)     # and not closed    or
            || ($final_open > $final_close)); # one opened after last close

    # Now check to see if we're inside a tag, matching a tag name, 
    # or attribute name or value
    $final_open  = rindex($before, "<") - $[;
    $final_close = rindex($before, ">") - $[;
    ($final_open >= 0)          # Tag opened 
        && (($final_close < 0)  # and not closed    or
            || ($final_open > $final_close)); # one opened after last close
}



# This subroutine looks a little odd.  Rather than build up some code
# and keep "eval"ing later, I'm building a new subroutine.  This way I
# can declare local vars and not worry about the namespace in the
# calling context.  I don't know how much it really gains me, but I
# don't know of any real costs and it seems like it could be
# friendlier to optimization.  (And it's fun to define new
# subroutines at runtime.  :-)

# I once thought that storing the finished dynamic_make_dictionary_links
# in a file and using it for subsequent invokations (when the
# dictionaries were the same) would save time.  I tried it, and the
# speed gain is insignificant.  (Using the standard links dictionary,
# it speeds up by 0.1 seconds per invokation on a 386/33 with a slow
# old hard drive.  I couldn't measure a difference on my fast machine.)

sub make_dictionary_links_code
{
    local($i,$pattern,$switches,$options,$code,$href);
    $code = <<EOCode;
sub dynamic_make_dictionary_links
{
    local(\$line_link) = (\$line_action | \$LINK);
    local(\$before,\$linkme,\$line_with_links);
EOCode
    for($i=1; $i <= $#links_table_order; $i++)
    {
        $pattern = $links_table_order[$i];
        $key = $pattern;
        $switches = $links_switch_table{$key};
        
        $s_sw = "";             # Options for searching
        $s_sw .= "i" if($switches & 1);
        
        $r_sw = "";             # Options for replacing
        $r_sw .= "i" if($switches & 1);
        $r_sw .= "e" if($switches & 2);

        $href = $links_table{$key};

        $href =~ s@/@\\/@g;
        $href = '<A HREF="' . $href . '">$&<\\/A>'
            if !($switches & 4);

        $code .= "    \$line_with_links = \"\";";
        if($switches & 8) # Do link only once
        {
        $code .= "
    while(!\$done_with_link[$i] && \$line =~ /$pattern/$s_sw)
    {
        \$done_with_link[$i] = 1;
";
        } else {
            $code .= "\n    while(\$line =~ /$pattern/$s_sw)\n    {";
        }
        $code .= <<EOCode;
        \$link_line = $LINK if(!\$link_line);
        \$before = \$\`;
        \$linkme = \$&;

        \$line = substr(\$line, length(\$before) + length(\$linkme));
        if(!&in_link_context(\$linkme,\$line_with_links . \$before))
        {
EOCode
        $code .= "            print STDERR \"Link rule $i matches \$linkme\\n\";
"
            if ($dict_debug & 4);

        $code .= <<EOCode;
            \$linkme =~ s/$pattern/$href/$r_sw;
        }
        \$line_with_links .= \$before . \$linkme;
    }
    \$line = \$line_with_links . \$line;
EOCode
    }
    $code .= <<EOCode;

    \$line_action |= \$line_link; # Cheaper only to do bitwise OR once.
}
EOCode
    print STDERR "$code" if ($dict_debug & 2);
    eval "$code";
    if($@)
    {
        print STDERR "Problem making dictionary eval code\n";
        die $@;
    }
    $code;
}

sub load_dictionary_links
{
    local( $dict, $contents );
    @links_table_order = 0;
    %links_table = ();
    
    foreach $dict ( @links_dictionaries )
    {
        next unless $dict;
        open(DICT, "$dict") || die "Can't open Dictionary file $dict\n";
        
        $contents = "";
        $contents .= $_ while(<DICT>);
        close(DICT);
        &parse_dict($dict, $contents);
    }
    &make_dictionary_links_code;
}

sub make_dictionary_links
{
    eval "&dynamic_make_dictionary_links;";
    warn $@ if $@;
}

sub getline
{
    local($line);
    $line = <>;
    $line = "" unless defined ($line);
    $line =~ s/[ \011]*\015$//; # Chop trailing whitespace and DOS CRs
    $line = &untabify($line);   # Change all tabs to spaces
    $line;
}

sub main
{
    $* = 1;                     # Turn on multiline searches
    push(@links_dictionaries,($default_link_dict)) 
        if ($make_links && (-f $default_link_dict));
    &deal_with_options;
    if($make_links)
    {
        push(@links_dictionaries,($system_link_dict)) if -f $system_link_dict;
        &load_dictionary_links;
    }

    $non_header_anchor = 0;

    # Moved this way up here so we can grab the first line and use it
    # as the title (if --titlefirst is set)
    $mode = 0;
    $listnum = 0;
    $list_indent = "";
    $line_action = $NONE;
    $prev_action = $NONE;
    $prev_line_length = 0;
    $prev_indent = 0;
    $prev     = "";
    $line     = &getline;
    $nextline = 0;
    $nextline = &getline if $line;

    # Skip leading blank lines
    while( &is_blank($line) && $line )
    {
        $prev = $line;
        $line = $nextline;
        $nextline = &getline if $nextline;
    }       

    if(!$extract)
    {
        print '<!DOCTYPE HTML PUBLIC "' . $doctype . "\">\n" unless !$doctype;
        print "<HTML>\n";
        print "<HEAD>\n";

        # if --titlefirst is set and --title isn't, use the first line
        # as the title.
        if ($titlefirst && !$title)
        {
            ($title) = $line =~ /^ *(.*)/; # grab first line
            $title =~ s/ *$//; # strip trailing whitespace
            $title = &escape( $title ) if $escape_HTML_chars;
        }
        $title = "" if !$title;
        print "<TITLE>$title</TITLE>\n";

        if ($append_head)
        {
            open(APPEND, $append_head) || die "Failed to open $append_head\n";
            print while <APPEND>;
            close(APPEND);
        }

        print "<META NAME=\"generator\" CONTENT=\"txt2html v$version\">\n";
        print "</HEAD>\n";
        print "<BODY>\n";
    }

    if ($prepend_file)
    {
        if(-r $prepend_file)
        {
            open( PREPEND, $prepend_file );
            print while <PREPEND>;
            close( PREPEND );
        } else {
            print STDERR "Can't find or read file $prepend_file to prepend.\n";
        }
    }

    do 
    {

        if ( !$link_only )
        {
          ###############
          # Need to move this stuff into getline
          #
          $line_length = length($line); # Do this before tags go in
          $line_indent = &count_indent($line, $prev_indent);
          #
          $line = &escape( $line ) if $escape_HTML_chars;
          #
          ###############

          &endpreformat if (($mode & $PRE) && ($preformat_trigger_lines != 0));

          &hrule if !($mode & $PRE);

          &custom_heading if (($#custom_heading_regexp > -1)
                              && !($mode & $PRE));

          &liststuff if (!($mode & $PRE) && 
                         !&is_blank($line));

          &heading   if (!$explicit_headings &&
                         !($mode & ($PRE | $HEADER)) && 
                         $nextline =~ /^\s*[=\-\*\.~\+]+\s*$/);

#        &custom_tag if (($#custom_tags > -1)
#                        && !($mode & $PRE)
#                        && !($line_action & $HEADER));

          &mailstuff if ($mailmode && 
                         !($mode & $PRE) && 
                         !($line_action & $HEADER));

          &preformat if (!($line_action & ($HEADER | $LIST | $MAILHEADER)) && 
                         !($mode & ($LIST | $PRE)) &&
                         $preformat_enabled);

          &paragraph;
          &shortline;

          &unhyphenate if ($unhyphenation && 
                           ($line =~ /[^\W\d_]\-$/) && # ends in hyphen
                           # next line starts w/letters
                           ($nextline =~ /^\s*[^\W\d_]/) && 
                           !($mode & ($PRE | $HEADER | $MAILHEADER | $BREAK)));

          &caps if  !($mode & $PRE);

        }

        &make_dictionary_links if ($make_links
                                   && !&is_blank($line)
                                   && $#links_table_order);

        # All the matching and formatting is done.  Now we can 
        # replace non-ASCII characters with character entities.
        if ( !$eight_bit_clean )
        {
            @chars = explode(//,$line);
            foreach $_ (@chars)
            {
                $_ = $char_entities{$_} if defined( $char_entities{$_} );
            }
            $line = join( "", @chars );
        }

        # Print it out and move on.

        print $prev;

        if (!&is_blank($nextline))
        {
            $prev_action = $line_action;
            $line_action     = $NONE;
            $prev_line_length = $line_length;
            $prev_indent = $line_indent;
        }

        $line = join( "", map { $char_entities2{$_} || $_ } split(//,$line) )
          unless $eight_bit_clean;
        $prev = $line;
        $line = $nextline;
        $nextline = &getline if $nextline;
    } until (!$nextline && !$line && !$prev);

    $prev = "";
    &endlist($listnum) if ($mode & $LIST); # End all lists
    print $prev;

    print "\n";

    print "</PRE>\n" if ($mode & $PRE);

    if ($append_file)
    {
        if(-r $append_file)
        {
            open(APPEND, $append_file);
            print while <APPEND>;
            close( APPEND );
        } else {
            print STDERR "Can't find or read file $append_file to append.\n";
        }
    }

    if(!$extract)
    {
        print "</BODY>\n";
        print "</HTML>\n";
    }
}

&main();

__END__
